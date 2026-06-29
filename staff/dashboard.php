<?php
require_once __DIR__ . '/../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
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

$userId = (int) $_SESSION['user_id'];

$stmt = $db->prepare('SELECT id, name, username, email, role, department, status, photo, created_at FROM users WHERE id = :id');
$stmt->execute([':id' => $userId]);
$user = $stmt->fetch();

$counts = $db->prepare("
    SELECT 
        SUM(CASE WHEN status = 'todo' THEN 1 ELSE 0 END) AS pending,
        SUM(CASE WHEN status = 'in-progress' THEN 1 ELSE 0 END) AS in_progress,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed
    FROM tasks WHERE assigned_to = :uid
");
$counts->execute([':uid' => $userId]);
$counts = $counts->fetch();

$businessName = '';
if (!empty($_SESSION['business_id'])) {
    $bStmt = $db->prepare('SELECT business_name FROM businesses WHERE id = :bid');
    $bStmt->execute([':bid' => $_SESSION['business_id']]);
    $businessName = $bStmt->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskHub — Staff Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    <link rel="stylesheet" href="../assets/css/app.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
</head>
<body class="min-h-screen">

    <!-- ─── NAVBAR ──────────────────────────────────── -->
    <nav class="navbar sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <img src="../assets/images/logo.png" alt="TaskHub" class="h-8">
                    <h1 class="text-xl font-bold"><span class="text-purple-500">Task</span>Hub</h1>
                    <span class="text-xs bg-emerald-500/20 text-emerald-500 px-2 py-0.5 rounded-full font-medium">Staff</span>
                </div>
                <div class="flex items-center gap-1 sm:gap-3 flex-wrap justify-end">
                    <button onclick="toggleTheme()" class="theme-toggle" title="Toggle theme"></button>

                    <!-- Profile Dropdown Trigger -->
                    <div class="relative" id="profileDropdownWrapper">
                        <button onclick="toggleProfileDropdown()" class="w-9 h-9 rounded-full overflow-hidden border-2 border-purple-500/50 flex items-center justify-center bg-[var(--bg-input)] cursor-pointer hover:opacity-80 transition flex-shrink-0" id="profileTrigger">
                            <?php if ($user['photo']): ?>
                                <img src="../uploads/profile/<?= htmlspecialchars($user['photo']) ?>" alt="" class="w-full h-full object-cover">
                            <?php else: ?>
                                <span class="text-sm font-bold text-purple-400"><?= strtoupper(substr($user['name'], 0, 1)) ?></span>
                            <?php endif; ?>
                        </button>
                        <!-- Dropdown Menu -->
                        <div id="profileDropdownMenu" class="absolute right-0 top-full mt-2 w-64 rounded-xl border shadow-xl hidden" style="background:var(--bg-page); border-color:var(--border-color); z-index:100;">
                            <div class="p-4">
                                <div class="flex items-center gap-3 mb-3 pb-3 border-b" style="border-color:var(--border-color);">
                                    <div class="relative w-12 h-12 rounded-full overflow-hidden border-2 border-purple-500/50 bg-[var(--bg-input)] flex items-center justify-center flex-shrink-0">
                                        <?php if ($user['photo']): ?>
                                            <img id="dropdownProfileImg" src="../uploads/profile/<?= htmlspecialchars($user['photo']) ?>" alt="" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <span class="text-lg font-bold text-purple-400"><?= strtoupper(substr($user['name'], 0, 1)) ?></span>
                                        <?php endif; ?>
                                        <label for="photoUpload" class="absolute bottom-0 right-0 w-5 h-5 bg-purple-600 rounded-full flex items-center justify-center cursor-pointer hover:bg-purple-700 transition shadow-lg" title="Change photo">
                                            <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            <input type="file" id="photoUpload" accept="image/*" class="hidden">
                                        </label>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-sm truncate"><?= htmlspecialchars($user['name']) ?></p>
                                        <p class="text-xs text-[var(--text-secondary)] truncate"><?= htmlspecialchars($user['username']) ?></p>
                                        <?php if ($businessName): ?>
                                            <p class="text-xs text-purple-400 truncate mt-0.5"><?= htmlspecialchars($businessName) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between"><span class="text-[var(--text-secondary)]">Department</span><span><?= htmlspecialchars($user['department'] ?? 'N/A') ?></span></div>
                                    <div class="flex justify-between"><span class="text-[var(--text-secondary)]">Status</span><span class="text-emerald-500"><?= htmlspecialchars($user['status']) ?></span></div>
                                    <div class="flex justify-between"><span class="text-[var(--text-secondary)]">Member Since</span><span><?= date('M Y', strtotime($user['created_at'])) ?></span></div>
                                </div>
                            </div>
                            <div class="border-t p-3" style="border-color:var(--border-color);">
                                <a href="../logout.php" class="block w-full text-center text-sm text-red-500 hover:text-red-400 font-medium transition">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- ─── SUB-NAV ─────────────────────────────────── -->
    <div class="border-b" style="background:var(--nav-bg); border-color:var(--border-color);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex gap-2 py-2">
                <button onclick="openWorkLogModal()" class="text-xs bg-purple-500/20 text-purple-400 hover:bg-purple-500/30 transition px-3 py-1.5 rounded-lg font-medium whitespace-nowrap">+ Log Work</button>
                <button onclick="viewMyLogs()" class="text-xs bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 transition px-3 py-1.5 rounded-lg font-medium whitespace-nowrap">My Logs</button>
                <a href="../usermanual.php#staff-guide" target="_blank" class="ml-auto text-xs text-purple-400 hover:text-purple-300 hover:bg-purple-500/10 transition px-3 py-1.5 rounded-lg font-medium whitespace-nowrap flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>Help</a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">

        <!-- ─── WELCOME SECTION ──────────────────────── -->
        <div class="card p-6 md:p-8 mb-8" style="background: linear-gradient(135deg, rgba(147,51,234,0.1), var(--bg-card));">
            <h2 class="text-2xl font-bold">Welcome, <?= htmlspecialchars(explode(' ', $_SESSION['name'])[0]) ?>!</h2>
            <p class="text-[var(--text-secondary)] mt-1">Here's your workload summary.</p>
            <div class="grid grid-cols-3 gap-3 sm:gap-4 mt-6 max-w-md">
                <div class="stat-card text-center" style="border-color: rgba(251,191,36,0.3);">
                        <p class="text-xl sm:text-2xl font-bold text-amber-500" id="welcomeTodoCount"><?= (int)$counts['pending'] ?></p>
                        <p class="text-xs text-[var(--text-secondary)] mt-1">To-Do</p>
                    </div>
                    <div class="stat-card text-center" style="border-color: rgba(59,130,246,0.3);">
                        <p class="text-xl sm:text-2xl font-bold text-blue-500" id="welcomeProgressCount"><?= (int)$counts['in_progress'] ?></p>
                        <p class="text-xs text-[var(--text-secondary)] mt-1">In Progress</p>
                    </div>
                    <div class="stat-card text-center" style="border-color: rgba(16,185,129,0.3);">
                        <p class="text-xl sm:text-2xl font-bold text-emerald-500" id="welcomeCompletedCount"><?= (int)$counts['completed'] ?></p>
                    <p class="text-xs text-[var(--text-secondary)] mt-1">Completed</p>
                </div>
            </div>
        </div>

        <!-- ─── TASK BOARD ───────────────────────────── -->
        <div>
            <div class="flex border-b mb-6 gap-1" style="border-color: var(--tab-border);">
                <button class="tab-btn active flex-1 text-center px-2 sm:px-6 py-3 text-sm font-medium border-b-2 border-purple-500 text-purple-500 transition whitespace-nowrap" data-tab="todo">
                    To-Do <span id="todoCount" class="ml-1.5 text-xs px-2 py-0.5 rounded-full" style="background:var(--bg-card); color:var(--text-secondary);">0</span>
                </button>
                <button class="tab-btn flex-1 text-center px-2 sm:px-6 py-3 text-sm font-medium border-b-2 border-transparent text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition whitespace-nowrap" data-tab="in-progress">
                    In Progress <span id="progressCount" class="ml-1.5 text-xs px-2 py-0.5 rounded-full" style="background:var(--bg-card); color:var(--text-secondary);">0</span>
                </button>
                <button class="tab-btn flex-1 text-center px-2 sm:px-6 py-3 text-sm font-medium border-b-2 border-transparent text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition whitespace-nowrap" data-tab="completed">
                    Completed <span id="completedCount" class="ml-1.5 text-xs px-2 py-0.5 rounded-full" style="background:var(--bg-card); color:var(--text-secondary);">0</span>
                </button>
            </div>

            <div id="taskList" class="space-y-3">
                <div class="text-center text-[var(--text-muted)] py-12">Loading tasks...</div>
            </div>
        </div>

    </div>

    <!-- ─── PROGRESS MODAL ───────────────────────── -->
    <div id="progressModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="rounded-2xl p-6 max-w-lg w-full shadow-2xl border" style="background:var(--bg-page); border-color:var(--border-color);">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Update Progress</h3>
                <button onclick="closeProgressModal()" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition text-2xl leading-none">&times;</button>
            </div>
            <p class="text-sm text-[var(--text-secondary)] mb-3">Task: <span id="progressTaskTitle" class="text-white font-medium"></span></p>
            <form id="progressForm" class="space-y-4">
                <input type="hidden" id="progressTaskId" value="">
                <div>
                    <label class="block text-xs text-[var(--text-secondary)] mb-1">What have you done so far? <span class="text-red-400">*</span></label>
                    <textarea id="progressMessage" rows="3" placeholder="Describe your progress..." required class="w-full"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeProgressModal()" class="btn-ghost">Cancel</button>
                    <button type="submit" class="btn-primary">Send Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ─── VIEW PROGRESS MODAL ──────────────────── -->
    <div id="viewProgressModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="rounded-2xl p-6 max-w-2xl w-full shadow-2xl border max-h-[90vh] overflow-y-auto" style="background:var(--bg-page); border-color:var(--border-color);">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Work Progress History <span class="text-xs text-emerald-500 font-normal ml-2">● Live</span></h3>
                <button onclick="closeViewProgressModal()" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition text-2xl leading-none">&times;</button>
            </div>
            <div id="viewProgressContent" class="space-y-3 mb-4 min-h-[80px]">
                <p class="text-center text-[var(--text-muted)] py-4">Loading...</p>
            </div>
            <input type="hidden" id="viewProgressTaskId" value="">
            <form id="viewProgressReplyForm" class="flex gap-3 pt-3 border-t" style="border-color:var(--border-color);">
                <textarea id="viewProgressReplyMessage" rows="2" placeholder="Reply to admin..." required class="w-full text-sm"></textarea>
                <button type="submit" class="btn-primary text-sm whitespace-nowrap self-end">Send</button>
            </form>
        </div>
    </div>

    <!-- ─── WORK LOG MODAL ────────────────────────── -->
    <div id="workLogModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="rounded-2xl p-6 max-w-lg w-full shadow-2xl border" style="background:var(--bg-page); border-color:var(--border-color);">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Log Your Work</h3>
                <button onclick="closeWorkLogModal()" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition text-2xl leading-none">&times;</button>
            </div>
            <form id="workLogForm" class="space-y-4">
                <div>
                    <label class="block text-xs text-[var(--text-secondary)] mb-1">What did you work on? <span class="text-red-400">*</span></label>
                    <textarea id="workLogDesc" name="description" rows="3" placeholder="Describe the work you did..." required class="w-full"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-[var(--text-secondary)] mb-1">Date</label>
                    <input type="date" id="workLogDate" name="log_date" class="w-full">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeWorkLogModal()" class="btn-ghost">Cancel</button>
                    <button type="submit" id="workLogSubmitBtn" class="btn-primary flex items-center gap-2">
                        <span id="workLogBtnText">Save</span>
                        <span id="workLogSpinner" class="hidden w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ─── MY LOGS MODAL ────────────────────────── -->
    <div id="myLogsModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="rounded-2xl p-6 max-w-3xl w-full shadow-2xl border max-h-[90vh] overflow-y-auto" style="background:var(--bg-page); border-color:var(--border-color);">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">My Work Logs</h3>
                <button onclick="closeMyLogsModal()" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition text-2xl leading-none">&times;</button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="block text-xs text-[var(--text-secondary)] mb-1">From Date</label>
                    <input type="date" id="myLogsDateFrom" class="w-full">
                </div>
                <div>
                    <label class="block text-xs text-[var(--text-secondary)] mb-1">To Date</label>
                    <input type="date" id="myLogsDateTo" class="w-full">
                </div>
            </div>
            <div class="flex gap-2 mb-4">
                <button onclick="applyMyLogsFilter()" class="btn-primary text-sm">Filter</button>
            </div>
            <div id="myLogsContent" class="space-y-3">
                <div class="text-center text-[var(--text-muted)] py-8">Loading...</div>
            </div>
        </div>
    </div>

    <!-- ─── STAFF REPLY MODAL ─────────────────────── -->
    <div id="staffReplyModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="rounded-2xl p-6 max-w-2xl w-full shadow-2xl border max-h-[90vh] overflow-y-auto" style="background:var(--bg-page); border-color:var(--border-color);">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Conversation</h3>
                <button onclick="closeStaffReplyModal()" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition text-2xl leading-none">&times;</button>
            </div>
            <div id="staffReplyOriginal" class="p-4 rounded-lg border mb-4" style="background:var(--bg-card); border-color:var(--border-color);"></div>
            <div id="staffReplyThread" class="space-y-3 mb-4"></div>
            <form id="staffReplyForm" class="flex gap-3">
                <input type="hidden" id="staffReplyWorkLogId" value="">
                <textarea id="staffReplyMessage" rows="2" placeholder="Type your reply..." required class="w-full text-sm"></textarea>
                <button type="submit" class="btn-primary text-sm whitespace-nowrap self-end">Send</button>
            </form>
        </div>
    </div>

    <!-- ─── CROP MODAL ────────────────────────────── -->
    <div id="cropModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="rounded-2xl p-6 max-w-lg w-full shadow-2xl border" style="background:var(--bg-page); border-color:var(--border-color);">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Crop Profile Photo</h3>
                <button onclick="closeCropModal()" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition text-2xl leading-none">&times;</button>
            </div>
            <div class="w-full max-h-[50vh] overflow-hidden rounded-lg bg-black/20 flex items-center justify-center">
                <img id="cropImage" src="" alt="Crop preview" class="max-w-full max-h-[50vh]">
            </div>
            <div class="flex items-center gap-3 mt-4">
                <button onclick="uploadCroppedPhoto()" id="cropUploadBtn" class="btn-primary flex-1 flex items-center justify-center gap-2">
                    <span id="cropBtnText">Upload</span>
                    <span id="cropSpinner" class="hidden w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                </button>
                <button onclick="closeCropModal()" class="btn-ghost">Cancel</button>
            </div>
            <div id="uploadProgressWrap" class="hidden mt-3">
                <div class="w-full bg-[var(--bg-input)] rounded-full h-2 overflow-hidden">
                    <div id="uploadProgressBar" class="bg-purple-600 h-2 rounded-full transition-all duration-300" style="width:0%"></div>
                </div>
                <p id="uploadProgressText" class="text-xs text-[var(--text-secondary)] mt-1 text-center">0%</p>
            </div>
        </div>
    </div>

    <footer class="border-t mt-12 py-6" style="border-color:var(--border-color);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs" style="color:var(--text-muted);">
            TaskHub &mdash; Task Management &amp; Workflow Automation System &mdash; Developed by <span class="text-purple-400">Aakash Gaud</span> (<button onclick="var b=this;var l=this.nextElementSibling;b.classList.add('hidden');l.classList.remove('hidden');setTimeout(function(){l.classList.add('hidden');b.classList.remove('hidden')},5000)" class="text-purple-400 hover:text-purple-300 bg-purple-500/10 px-1.5 py-0.5 rounded text-xs font-medium inline-flex items-center gap-1">Show Email</button><a href="mailto:akashgaud7389@gmail.com" class="text-purple-400 hover:text-purple-300 hidden">akashgaud7389@gmail.com</a>) &copy; 2026 All Rights Reserved
        </div>
    </footer>

    <script defer src="../assets/js/app.js"></script>
    <script defer src="../assets/js/staff.js"></script>
</body>
</html>
