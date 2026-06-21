<?php
require_once __DIR__ . '/../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

try {
    $db = getBizDB();

    if (!empty($_SESSION['session_token'])) {
        $tokCheck = $db->prepare('SELECT session_token FROM users WHERE id = :id');
        $tokCheck->execute([':id' => $_SESSION['user_id']]);
        if ($tokCheck->fetchColumn() !== $_SESSION['session_token']) {
            session_destroy();
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
            header('Location: ../index.php');
            exit;
        }
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo '<!DOCTYPE html><html><body style="font-family:sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;background:#0f172a;color:#fff;margin:0"><div style="text-align:center"><h1>Database Error</h1><p>Could not connect to database. Please try again later.</p></div></body></html>';
    exit;
}

$stats = [];
$bizId = !empty($_SESSION['business_id']) ? (int)$_SESSION['business_id'] : null;

$statSqls = [
    'total_staff' => "SELECT COUNT(*) FROM users WHERE role = 'staff'" . ($bizId ? " AND business_id = $bizId" : ''),
    'total_tasks' => $bizId ? "SELECT COUNT(*) FROM tasks t JOIN users u ON t.assigned_to = u.id WHERE u.business_id = $bizId" : 'SELECT COUNT(*) FROM tasks',
    'pending_tasks' => $bizId ? "SELECT COUNT(*) FROM tasks t JOIN users u ON t.assigned_to = u.id WHERE t.status = 'todo' AND u.business_id = $bizId" : "SELECT COUNT(*) FROM tasks WHERE status = 'todo'",
    'in_progress_tasks' => $bizId ? "SELECT COUNT(*) FROM tasks t JOIN users u ON t.assigned_to = u.id WHERE t.status = 'in-progress' AND u.business_id = $bizId" : "SELECT COUNT(*) FROM tasks WHERE status = 'in-progress'",
    'completed_tasks' => $bizId ? "SELECT COUNT(*) FROM tasks t JOIN users u ON t.assigned_to = u.id WHERE t.status = 'completed' AND u.business_id = $bizId" : "SELECT COUNT(*) FROM tasks WHERE status = 'completed'",
];
foreach ($statSqls as $k => $sql) { $stats[$k] = $db->query($sql)->fetchColumn(); }

if ($bizId) {
    $tmp = $db->prepare("SELECT id, name, department FROM users WHERE role = 'staff' AND status = 'active' AND business_id = :bid ORDER BY name");
    $tmp->execute([':bid' => $bizId]); $activeStaff = $tmp->fetchAll();
    $tmp = $db->prepare("SELECT id, name, department, status, photo FROM users WHERE role = 'staff' AND business_id = :bid ORDER BY name");
    $tmp->execute([':bid' => $bizId]); $allStaff = $tmp->fetchAll();
    $tmp = $db->prepare("SELECT id, name FROM departments WHERE business_id = :bid ORDER BY CASE WHEN name = 'Management' THEN 0 ELSE 1 END, name ASC");
    $tmp->execute([':bid' => $bizId]); $departments = $tmp->fetchAll();
} else {
    $activeStaff = $db->query("SELECT id, name, department FROM users WHERE role = 'staff' AND status = 'active' ORDER BY name")->fetchAll();
    $allStaff = $db->query("SELECT id, name, department, status, photo FROM users WHERE role = 'staff' ORDER BY name")->fetchAll();
    $departments = $db->query("SELECT id, name FROM departments ORDER BY CASE WHEN name = 'Management' THEN 0 ELSE 1 END, name ASC")->fetchAll();
}

$business = null;
$mainDb = getDB();
if (!empty($_SESSION['business_id'])) {
    $bizStmt = $mainDb->prepare('SELECT * FROM businesses WHERE id = :id');
    $bizStmt->execute([':id' => $_SESSION['business_id']]);
    $business = $bizStmt->fetch();
} else {
    $bizStmt = $mainDb->prepare('SELECT b.*, u.id AS user_id FROM users u LEFT JOIN businesses b ON u.business_id = b.id WHERE u.id = :uid');
    $bizStmt->execute([':uid' => $_SESSION['user_id']]);
    $bizData = $bizStmt->fetch();
    if ($bizData && $bizData['id']) {
        $business = $bizData;
        $_SESSION['business_id'] = $bizData['id'];
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskHub — Admin Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/app.css">
</head>
<body class="min-h-screen">

    <!-- ─── NAVBAR ──────────────────────────────────── -->
    <nav class="navbar sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <img src="../assets/images/logo.png" alt="TaskHub" class="h-8">
                    <h1 class="text-xl font-bold"><span class="text-purple-500">Task</span>Hub</h1>
                    <span class="text-xs bg-purple-500/20 text-purple-500 px-2 py-0.5 rounded-full font-medium">Admin</span>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="toggleTheme()" class="theme-toggle" title="Toggle theme"></button>
                    <span class="text-sm text-[var(--text-secondary)] hidden sm:inline"><?= htmlspecialchars($_SESSION['name']) ?></span>
                    <a href="../logout.php" class="text-sm text-[var(--text-secondary)] hover:text-red-500 transition">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ─── SUB-NAV ─────────────────────────────────── -->
    <div class="border-b" style="background:var(--nav-bg); border-color:var(--border-color);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex gap-1 py-2 overflow-x-auto">
                <button onclick="switchSection('dashboard')" id="navDashboard" class="nav-btn active px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition" style="background:var(--bg-card); color:var(--text-primary);">Dashboard</button>
                <button onclick="switchSection('staff')" id="navStaff" class="nav-btn px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition" style="color:var(--text-secondary);">Staff Management</button>
                <button onclick="switchSection('departments')" id="navDepartments" class="nav-btn px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition" style="color:var(--text-secondary);">Department Management</button>
                <button onclick="switchSection('activity')" id="navActivity" class="nav-btn px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition" style="color:var(--text-secondary);">
                    Staff Activity <span id="activityBadge" class="hidden ml-1 px-1.5 py-0.5 text-xs rounded-full bg-purple-600 text-white font-bold">0</span>
                </button>
                <button onclick="switchSection('profile')" id="navProfile" class="nav-btn px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition" style="color:var(--text-secondary);">Business Profile</button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <?php if (isset($_GET['setup'])): ?>
        <!-- ─── SETUP BANNER ─────────────────────────── -->
        <div id="setupBanner" class="card p-6 mb-8 border-purple-500/30" style="background: linear-gradient(135deg, rgba(147,51,234,0.15), var(--bg-card));">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-purple-400 mb-2">Welcome to TaskHub! 🎉</h2>
                    <p class="text-[var(--text-secondary)] text-sm mb-4">Your business is registered. Get started by adding your team and setting up departments.</p>
                    <div class="flex gap-3 flex-wrap">
                        <a href="#" onclick="switchSection('staff'); document.getElementById('staffForm').classList.remove('hidden'); document.getElementById('setupBanner').classList.add('hidden'); return false;" class="btn-primary text-sm">+ Add Staff</a>
                        <a href="#" onclick="switchSection('departments'); document.getElementById('deptForm').classList.remove('hidden'); document.getElementById('setupBanner').classList.add('hidden'); return false;" class="btn-primary text-sm" style="background:var(--bg-card); color:var(--text-primary); border:1px solid var(--border-color);">+ Add Departments</a>
                        <button onclick="document.getElementById('setupBanner').classList.add('hidden')" class="btn-ghost text-sm">Skip</button>
                    </div>
                </div>
                <button onclick="document.getElementById('setupBanner').classList.add('hidden')" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition text-xl leading-none">&times;</button>
            </div>
        </div>
        <?php endif; ?>

        <!-- ═══ SECTION: DASHBOARD ═══════════════════ -->
        <div id="sectionDashboard">

            <!-- ─── TOP STATS BAR ────────────────────── -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                <div class="stat-card">
                    <p class="text-[var(--text-secondary)] text-xs uppercase tracking-wider">Total Staff</p>
                    <p class="text-3xl font-bold mt-1"><?= $stats['total_staff'] ?></p>
                </div>
                <div class="stat-card">
                    <p class="text-[var(--text-secondary)] text-xs uppercase tracking-wider">Total Tasks</p>
                    <p class="text-3xl font-bold mt-1"><?= $stats['total_tasks'] ?></p>
                </div>
                <div class="stat-card" style="border-color: rgba(251,191,36,0.3);">
                    <p class="text-[var(--text-secondary)] text-xs uppercase tracking-wider">Pending</p>
                    <p class="text-3xl font-bold mt-1 text-amber-500"><?= $stats['pending_tasks'] ?></p>
                </div>
                <div class="stat-card" style="border-color: rgba(59,130,246,0.3);">
                    <p class="text-[var(--text-secondary)] text-xs uppercase tracking-wider">In Progress</p>
                    <p class="text-3xl font-bold mt-1 text-blue-500"><?= $stats['in_progress_tasks'] ?></p>
                </div>
                <div class="stat-card" style="border-color: rgba(16,185,129,0.3);">
                    <p class="text-[var(--text-secondary)] text-xs uppercase tracking-wider">Completed</p>
                    <p class="text-3xl font-bold mt-1 text-emerald-500"><?= $stats['completed_tasks'] ?></p>
                </div>
            </div>

            <!-- ─── TASK ASSIGNMENT ──────────────────── -->
            <div class="card p-6 mb-8">
                <h2 class="text-lg font-semibold mb-6">Assign New Task</h2>
                <form id="taskForm" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="lg:col-span-2">
                        <label class="block text-xs text-[var(--text-secondary)] mb-1">Task Title <span class="text-red-400">*</span></label>
                        <input type="text" id="taskTitle" name="title" placeholder="Enter task title..." required class="w-full">
                    </div>
                    <div>
                        <label class="block text-xs text-[var(--text-secondary)] mb-1">Assign To <span class="text-red-400">*</span></label>
                        <select id="taskAssignedTo" name="assigned_to" required class="w-full">
                            <option value="">Select staff...</option>
                            <?php foreach ($activeStaff as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['department'] ?? 'N/A') ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-[var(--text-secondary)] mb-1">Priority</label>
                        <select id="taskPriority" name="priority" class="w-full">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <div class="lg:col-span-2">
                        <label class="block text-xs text-[var(--text-secondary)] mb-1">Description</label>
                        <textarea id="taskDescription" name="description" rows="2" placeholder="Detailed description..." class="w-full"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs text-[var(--text-secondary)] mb-1">Due Date <span class="text-red-400">*</span></label>
                        <input type="date" id="taskDueDate" required class="w-full">
                    </div>
                    <div>
                        <label class="block text-xs text-[var(--text-secondary)] mb-1">Due Time <span class="text-[var(--text-muted)]">(optional)</span></label>
                        <input type="time" id="taskDueTime" class="w-full">
                    </div>
                    <div class="flex items-end lg:col-span-2">
                        <button type="submit" class="btn-primary w-full lg:w-auto">Assign Task</button>
                    </div>
                </form>
            </div>

            <!-- ─── TASK TRACKER ─────────────────────── -->
            <div class="card p-6">
                <h2 class="text-lg font-semibold mb-6">Task Tracker</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
                    <div>
                        <label class="block text-xs text-[var(--text-secondary)] mb-1">Staff</label>
                        <select id="filterStaff" class="w-full">
                            <option value="">All Staff</option>
                            <?php foreach ($allStaff as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?><?= $s['status'] !== 'active' ? ' (inactive)' : '' ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-[var(--text-secondary)] mb-1">Priority</label>
                        <select id="filterPriority" class="w-full">
                            <option value="">All Priorities</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-[var(--text-secondary)] mb-1">From Date</label>
                        <input type="date" id="filterDateFrom" class="w-full">
                    </div>
                    <div>
                        <label class="block text-xs text-[var(--text-secondary)] mb-1">To Date</label>
                        <input type="date" id="filterDateTo" class="w-full">
                    </div>
                </div>

                <div class="mb-4 flex gap-2 flex-wrap items-center">
                    <span class="text-xs text-[var(--text-secondary)] font-medium mr-1">Status:</span>
                    <button class="filter-btn active bg-purple-600 text-white px-3 py-1.5 rounded-lg text-sm" data-filter="all">All</button>
                    <button class="filter-btn bg-slate-700 text-slate-300 px-3 py-1.5 rounded-lg text-sm hover:bg-slate-600" data-filter="todo">Pending</button>
                    <button class="filter-btn bg-slate-700 text-slate-300 px-3 py-1.5 rounded-lg text-sm hover:bg-slate-600" data-filter="in-progress">In Progress</button>
                    <button class="filter-btn bg-slate-700 text-slate-300 px-3 py-1.5 rounded-lg text-sm hover:bg-slate-600" data-filter="completed">Completed</button>
                    <button onclick="applyFilters()" class="btn-primary text-sm ml-auto">Apply Filters</button>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Assigned To</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Notes</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="taskTableBody">
                            <tr><td colspan="8" class="text-center text-[var(--text-muted)] py-8">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ═══ SECTION: PROFILE ═════════════════════ -->
        <div id="sectionProfile" class="hidden">
            <div class="card p-6 md:p-8 max-w-3xl">
                <h2 class="text-lg font-semibold mb-6">Business Profile</h2>
                <?php if ($business): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs text-[var(--text-secondary)] uppercase tracking-wider mb-1">Business Name</p>
                        <p class="text-sm font-medium"><?= htmlspecialchars($business['business_name']) ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-[var(--text-secondary)] uppercase tracking-wider mb-1">Owner Name</p>
                        <p class="text-sm font-medium"><?= htmlspecialchars($business['owner_name']) ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-[var(--text-secondary)] uppercase tracking-wider mb-1">Email</p>
                        <p class="text-sm font-medium"><?= htmlspecialchars($business['email']) ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-[var(--text-secondary)] uppercase tracking-wider mb-1">Phone</p>
                        <p class="text-sm font-medium"><?= htmlspecialchars($business['phone'] ?? '—') ?></p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs text-[var(--text-secondary)] uppercase tracking-wider mb-1">Business Code</p>
                        <p class="text-sm font-mono font-bold text-purple-400"><?= htmlspecialchars($business['business_code']) ?></p>
                        <p class="text-xs text-[var(--text-secondary)] mt-1">Share this code with your staff so they can log in.</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs text-[var(--text-secondary)] uppercase tracking-wider mb-1">Address</p>
                        <p class="text-sm"><?= nl2br(htmlspecialchars($business['address'] ?? '—')) ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-[var(--text-secondary)] uppercase tracking-wider mb-1">Status</p>
                        <p class="text-sm"><span class="<?= $business['status'] === 'active' ? 'text-emerald-500' : 'text-red-500' ?>"><?= ucfirst(htmlspecialchars($business['status'])) ?></span></p>
                    </div>
                    <div>
                        <p class="text-xs text-[var(--text-secondary)] uppercase tracking-wider mb-1">Registered On</p>
                        <p class="text-sm"><?= date('M d, Y', strtotime($business['created_at'])) ?></p>
                    </div>
                </div>
                <?php else: ?>
                <p class="text-[var(--text-muted)] text-sm">Business profile not available.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- ═══ SECTION: STAFF MANAGEMENT ════════════ -->
        <div id="sectionStaff" class="hidden">
            <div class="card p-6 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold">Staff Management</h2>
                    <button onclick="document.getElementById('staffForm').classList.toggle('hidden')"
                            class="btn-primary text-sm">+ New Staff</button>
                </div>

                <form id="staffForm" class="hidden mb-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 fade-in">
                    <input type="hidden" id="staffId" name="id" value="">
                    <div>
                        <label class="block text-xs text-[var(--text-secondary)] mb-1">Full Name <span class="text-red-400">*</span></label>
                        <input type="text" id="staffName" name="name" placeholder="John Doe" required class="w-full">
                    </div>
                    <div>
                        <label class="block text-xs text-[var(--text-secondary)] mb-1">Username <span class="text-red-400">*</span></label>
                        <input type="text" id="staffUsername" name="username" placeholder="johndoe" required class="w-full">
                    </div>
                    <div>
                        <label class="block text-xs text-[var(--text-secondary)] mb-1">Password <span class="text-red-400">*</span></label>
                        <input type="text" id="staffPassword" name="password" placeholder="Min 8 chars for new" class="w-full">
                    </div>
                    <div>
                        <label class="block text-xs text-[var(--text-secondary)] mb-1">Department</label>
                        <select id="staffDepartment" name="department" class="w-full">
                            <option value="">Select...</option>
                            <?php foreach ($departments as $d): ?>
                                <?php if (strcasecmp($d['name'], 'Management') === 0) continue; ?>
                                <option value="<?= htmlspecialchars($d['name']) ?>"><?= htmlspecialchars($d['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="btn-primary flex-1" id="staffSubmitBtn">Create</button>
                        <button type="button" onclick="resetStaffForm()" class="btn-ghost text-sm">Reset</button>
                    </div>
                </form>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="staffTableBody">
                            <tr><td colspan="6" class="text-center text-[var(--text-muted)] py-8">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ═══ SECTION: DEPARTMENTS ═════════════════ -->
        <div id="sectionDepartments" class="hidden">
            <div class="card p-6 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold">Department Management</h2>
                    <button onclick="document.getElementById('deptForm').classList.toggle('hidden')"
                            class="btn-primary text-sm">+ New Department</button>
                </div>

                <form id="deptForm" class="hidden mb-6 flex items-end gap-3 fade-in">
                    <div class="flex-1">
                        <label class="block text-xs text-[var(--text-secondary)] mb-1">Department Name <span class="text-red-400">*</span></label>
                        <input type="text" id="deptName" name="name" placeholder="e.g. HR, Finance..." required class="w-full">
                    </div>
                    <button type="submit" class="btn-primary">Create</button>
                </form>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr><th>Department</th><th>Created</th><th>Actions</th></tr>
                        </thead>
                        <tbody id="deptTableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ═══ SECTION: STAFF ACTIVITY ══════════════ -->
        <div id="sectionActivity" class="hidden">
            <div class="card p-6">
                <h2 class="text-lg font-semibold mb-6">Staff Activity Log</h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                    <div>
                        <label class="block text-xs text-[var(--text-secondary)] mb-1">Staff Member</label>
                        <select id="activityFilterStaff" class="w-full">
                            <option value="">All Staff</option>
                            <?php foreach ($allStaff as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-[var(--text-secondary)] mb-1">From Date</label>
                        <input type="date" id="activityDateFrom" class="w-full">
                    </div>
                    <div>
                        <label class="block text-xs text-[var(--text-secondary)] mb-1">To Date</label>
                        <input type="date" id="activityDateTo" class="w-full">
                    </div>
                </div>

                <div class="flex gap-2 mb-4">
                    <button onclick="applyActivityFilter()" class="btn-primary text-sm">Filter</button>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr><th>Staff</th><th>Department</th><th>Work Done</th><th>Date</th><th>Logged At</th><th>Actions</th></tr>
                        </thead>
                        <tbody id="activityTableBody">
                            <tr><td colspan="6" class="text-center text-[var(--text-muted)] py-8">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- ─── EDIT TASK MODAL ────────────────────────── -->
    <div id="taskModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="rounded-2xl p-6 max-w-2xl w-full shadow-2xl border max-h-[90vh] overflow-y-auto" style="background:var(--bg-page); border-color:var(--border-color);">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold">Edit Task</h3>
                <button onclick="closeTaskModal()" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition text-2xl leading-none">&times;</button>
            </div>
            <form id="editTaskForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" id="editTaskId" value="">
                <div class="md:col-span-2">
                    <label class="block text-xs text-[var(--text-secondary)] mb-1">Task Title</label>
                    <input type="text" id="editTaskTitle" required class="w-full">
                </div>
                <div>
                    <label class="block text-xs text-[var(--text-secondary)] mb-1">Assign To</label>
                    <select id="editTaskAssignedTo" class="w-full">
                        <option value="">Select staff...</option>
                        <?php foreach ($activeStaff as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['department'] ?? 'N/A') ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-[var(--text-secondary)] mb-1">Priority</label>
                    <select id="editTaskPriority" class="w-full">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-[var(--text-secondary)] mb-1">Status</label>
                    <select id="editTaskStatus" class="w-full">
                        <option value="todo">Pending</option>
                        <option value="in-progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-[var(--text-secondary)] mb-1">Due Date</label>
                    <input type="date" id="editTaskDueDate" class="w-full">
                </div>
                <div>
                    <label class="block text-xs text-[var(--text-secondary)] mb-1">Due Time <span class="text-[var(--text-muted)]">(optional)</span></label>
                    <input type="time" id="editTaskDueTime" class="w-full">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs text-[var(--text-secondary)] mb-1">Description</label>
                    <textarea id="editTaskDescription" rows="3" class="w-full"></textarea>
                </div>
                <div class="md:col-span-2">
                </div>
                <div class="md:col-span-2 flex justify-end gap-3">
                    <button type="button" onclick="closeTaskModal()" class="btn-ghost">Cancel</button>
                    <button type="submit" class="btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <footer class="border-t mt-12 py-6" style="border-color:var(--border-color);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs" style="color:var(--text-muted);">
            TaskHub &mdash; Task Management &amp; Workflow Automation System<br>
            Developed by <span class="font-semibold text-purple-400">Aakash Gaud</span> (akashgaud7389@gmail.com) &copy; 2026 All Rights Reserved
        </div>
    </footer>

    <!-- ─── WORK LOG REPLY MODAL ────────────────── -->
    <div id="workLogReplyModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="rounded-2xl p-6 max-w-2xl w-full shadow-2xl border max-h-[90vh] overflow-y-auto" style="background:var(--bg-page); border-color:var(--border-color);">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Work Log Details</h3>
                <button onclick="closeWorkLogReplyModal()" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition text-2xl leading-none">&times;</button>
            </div>
            <div id="workLogReplyOriginal" class="p-4 rounded-lg border mb-4" style="background:var(--bg-card); border-color:var(--border-color);"></div>
            <div id="workLogReplyThread" class="space-y-3 mb-4"></div>
            <form id="workLogReplyForm" class="flex gap-3">
                <input type="hidden" id="replyWorkLogId" value="">
                <textarea id="replyMessage" rows="2" placeholder="Type your reply..." required class="w-full text-sm"></textarea>
                <button type="submit" class="btn-primary text-sm whitespace-nowrap self-end">Send</button>
            </form>
        </div>
    </div>

    <!-- ─── NOTE MODAL ────────────────────────────── -->
    <div id="noteModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="rounded-2xl p-6 max-w-lg w-full shadow-2xl border" style="background:var(--bg-page); border-color:var(--border-color);">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Work Notes</h3>
                <button onclick="closeNoteModal()" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition text-2xl leading-none">&times;</button>
            </div>
            <div class="p-4 rounded-lg border text-sm whitespace-pre-wrap" style="background:var(--notes-bg); border-color:var(--notes-border); color:var(--text-primary);" id="noteModalContent"></div>
            <div class="mt-4 flex justify-end">
                <button onclick="closeNoteModal()" class="btn-ghost">Close</button>
            </div>
        </div>
    </div>

    <script src="../assets/js/app.js"></script>
    <script src="../assets/js/admin.js"></script>
</body>
</html>
