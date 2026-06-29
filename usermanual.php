<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Manual — TaskHub</title>
<meta name="description" content="Complete TaskHub user manual. Learn how to use task management, staff tracking, work logs, and all features.">
<link rel="icon" type="image/x-icon" href="favicon.ico">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{font-family:'Inter',sans-serif}
html{scroll-behavior:smooth}
.manual-nav{position:sticky;top:0;z-index:50;background:rgba(15,23,42,.9);backdrop-filter:blur(12px);border-bottom:1px solid rgba(255,255,255,.05)}
.toc-item{cursor:pointer;transition:all .2s}
.toc-item:hover{color:#a78bfa;padding-left:4px}
.toc-item.active{color:#a78bfa;font-weight:600}
.section-card{transition:all .3s ease}
.section-card:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(147,51,234,.1)}
code{background:rgba(147,51,234,.15);color:#c084fc;padding:2px 6px;border-radius:4px;font-size:.9em}
kbd{background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);padding:2px 6px;border-radius:4px;font-size:.85em}
.step-num{display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:rgba(147,51,234,.2);color:#a78bfa;font-weight:700;font-size:.85rem;flex-shrink:0}
</style>
</head>
<body class="bg-slate-900 text-white antialiased">

<!-- ═══ NAVBAR ═══ -->
<nav class="manual-nav">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
<a href="index.php" class="flex items-center gap-2"><div class="w-7 h-7 rounded bg-purple-600 flex items-center justify-center text-xs font-bold">T</div><span class="font-bold"><span class="text-purple-400">Task</span>Hub</span></a>
<div class="hidden md:flex items-center gap-6 text-sm">
<a href="index.php" class="text-slate-300 hover:text-white transition">Home</a>
<a href="features.php" class="text-slate-300 hover:text-white transition">Features</a>
<a href="about.php" class="text-slate-300 hover:text-white transition">About</a>
<a href="contact.php" class="text-slate-300 hover:text-white transition">Contact</a>
</div>
<div class="flex items-center gap-3">
<a href="register.php" class="text-sm text-slate-300 hover:text-white transition hidden sm:inline">Register</a>
<a href="login.php" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">Login</a>
</div>
</div>
</nav>

<!-- ═══ HERO ═══ -->
<section class="bg-gradient-to-b from-purple-900/20 to-transparent py-16 border-b border-white/5">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
<h1 class="text-4xl sm:text-5xl font-extrabold mb-4"><span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-blue-400">User Manual</span></h1>
<p class="text-slate-400 max-w-2xl mx-auto text-lg">Complete guide to using TaskHub — from registration to advanced features. Everything you need to manage your team and tasks effectively.</p>
<div class="flex flex-wrap justify-center gap-4 mt-8 text-sm">
<a href="#getting-started" class="px-4 py-2 bg-white/5 rounded-lg border border-white/10 text-slate-300 hover:text-white transition">Getting Started</a>
<a href="#admin-guide" class="px-4 py-2 bg-white/5 rounded-lg border border-white/10 text-slate-300 hover:text-white transition">Admin Guide</a>
<a href="#staff-guide" class="px-4 py-2 bg-white/5 rounded-lg border border-white/10 text-slate-300 hover:text-white transition">Staff Guide</a>
<a href="#features" class="px-4 py-2 bg-white/5 rounded-lg border border-white/10 text-slate-300 hover:text-white transition">Features</a>
<a href="#faq" class="px-4 py-2 bg-white/5 rounded-lg border border-white/10 text-slate-300 hover:text-white transition">FAQ</a>
</div>
</div>
</section>

<!-- ═══ MAIN ═══ -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

<div class="grid lg:grid-cols-4 gap-8">

<!-- ═══ TABLE OF CONTENTS ═══ -->
<aside class="lg:col-span-1">
<div class="lg:sticky lg:top-24 space-y-1 text-sm">
<div class="font-semibold text-purple-400 mb-3 uppercase tracking-wider text-xs">Contents</div>
<a href="#overview" class="toc-item block text-slate-400 hover:text-purple-400 transition py-1">Overview</a>
<a href="#getting-started" class="toc-item block text-slate-400 hover:text-purple-400 transition py-1">Getting Started</a>
<a href="#admin-guide" class="toc-item block text-slate-400 hover:text-purple-400 transition py-1">Admin Guide</a>
<a href="#admin-dashboard" class="toc-item block text-slate-400 hover:text-purple-400 transition py-1 pl-3 text-xs">— Dashboard</a>
<a href="#admin-staff" class="toc-item block text-slate-400 hover:text-purple-400 transition py-1 pl-3 text-xs">— Staff Management</a>
<a href="#admin-departments" class="toc-item block text-slate-400 hover:text-purple-400 transition py-1 pl-3 text-xs">— Departments</a>
<a href="#admin-tasks" class="toc-item block text-slate-400 hover:text-purple-400 transition py-1 pl-3 text-xs">— Task Management</a>
<a href="#admin-activity" class="toc-item block text-slate-400 hover:text-purple-400 transition py-1 pl-3 text-xs">— Activity Tracking</a>
<a href="#admin-profile" class="toc-item block text-slate-400 hover:text-purple-400 transition py-1 pl-3 text-xs">— Business Profile</a>
<a href="#staff-guide" class="toc-item block text-slate-400 hover:text-purple-400 transition py-1">Staff Guide</a>
<a href="#staff-tasks" class="toc-item block text-slate-400 hover:text-purple-400 transition py-1 pl-3 text-xs">— Tasks</a>
<a href="#staff-worklog" class="toc-item block text-slate-400 hover:text-purple-400 transition py-1 pl-3 text-xs">— Work Logs</a>
<a href="#staff-replies" class="toc-item block text-slate-400 hover:text-purple-400 transition py-1 pl-3 text-xs">— Conversations</a>
<a href="#staff-profile" class="toc-item block text-slate-400 hover:text-purple-400 transition py-1 pl-3 text-xs">— Profile</a>
<a href="#features" class="toc-item block text-slate-400 hover:text-purple-400 transition py-1">Features Overview</a>
<a href="#faq" class="toc-item block text-slate-400 hover:text-purple-400 transition py-1">FAQ</a>
</div>
</aside>

<!-- ═══ CONTENT ═══ -->
<main class="lg:col-span-3 space-y-16">

<!-- ─── OVERVIEW ─── -->
<section id="overview">
<h2 class="text-2xl font-bold mb-4">What is TaskHub?</h2>
<p class="text-slate-400 leading-relaxed mb-4">
TaskHub is a comprehensive task management and workflow automation system designed for businesses of all sizes. It enables administrators to assign tasks, manage staff, track work logs, and communicate with team members — all from a single, intuitive dashboard.
</p>
<div class="grid sm:grid-cols-3 gap-4 mt-6">
<div class="section-card bg-white/5 rounded-xl p-5 border border-white/10">
<div class="w-10 h-10 rounded-lg bg-purple-600/20 flex items-center justify-center mb-3"><svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
<h3 class="font-semibold text-sm mb-1">Task Management</h3>
<p class="text-xs text-slate-500">Create, assign, prioritize, and track tasks with real-time updates.</p>
</div>
<div class="section-card bg-white/5 rounded-xl p-5 border border-white/10">
<div class="w-10 h-10 rounded-lg bg-blue-600/20 flex items-center justify-center mb-3"><svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/></svg></div>
<h3 class="font-semibold text-sm mb-1">Staff &amp; Team</h3>
<p class="text-xs text-slate-500">Manage your team, assign departments, and control access levels.</p>
</div>
<div class="section-card bg-white/5 rounded-xl p-5 border border-white/10">
<div class="w-10 h-10 rounded-lg bg-emerald-600/20 flex items-center justify-center mb-3"><svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></div>
<h3 class="font-semibold text-sm mb-1">Activity Tracking</h3>
<p class="text-xs text-slate-500">Monitor work logs, track daily activity, and review team performance.</p>
</div>
</div>
</section>

<!-- ─── GETTING STARTED ─── -->
<section id="getting-started">
<h2 class="text-2xl font-bold mb-6">Getting Started</h2>

<div class="space-y-8">

<div class="section-card bg-white/5 rounded-xl p-6 border border-white/10">
<h3 class="text-lg font-semibold mb-3">1. Register Your Business</h3>
<p class="text-sm text-slate-400 leading-relaxed mb-4">The first step is to register your business. Click the <strong>"Register"</strong> button on the homepage and fill out the registration form:</p>
<ul class="space-y-2 text-sm text-slate-400">
<li class="flex items-start gap-3"><span class="step-num mt-0.5">1</span> <span>Enter your <strong>Business Name</strong> — this will be displayed to all staff members.</span></li>
<li class="flex items-start gap-3"><span class="step-num mt-0.5">2</span> <span>Enter <strong>Owner Name</strong> — the person responsible for the business account.</span></li>
<li class="flex items-start gap-3"><span class="step-num mt-0.5">3</span> <span>Provide a valid <strong>Email</strong> address for important notifications.</span></li>
<li class="flex items-start gap-3"><span class="step-num mt-0.5">4</span> <span>Enter <strong>Phone</strong> number (optional but recommended).</span></li>
<li class="flex items-start gap-3"><span class="step-num mt-0.5">5</span> <span>Choose a <strong>Username</strong> and <strong>Password</strong> (minimum 8 characters, must contain uppercase, lowercase, and number).</span></li>
<li class="flex items-start gap-3"><span class="step-num mt-0.5">6</span> <span>Enter your <strong>Address</strong> (optional).</span></li>
</ul>
<p class="text-sm text-slate-400 mt-4">After registration, you will receive a <strong>Business Code</strong> (e.g., <code>GAYA-71799</code>). Share this code with your staff so they can log in.</p>
</div>

<div class="section-card bg-white/5 rounded-xl p-6 border border-white/10">
<h3 class="text-lg font-semibold mb-3">2. Login as Admin</h3>
<p class="text-sm text-slate-400 leading-relaxed mb-4">Once registered, you can log in as an Admin:</p>
<ul class="space-y-2 text-sm text-slate-400">
<li class="flex items-start gap-3"><span class="step-num mt-0.5">1</span> <span>Click <strong>"Login"</strong> from the homepage.</span></li>
<li class="flex items-start gap-3"><span class="step-num mt-0.5">2</span> <span>Select the <strong>"Admin"</strong> role tab.</span></li>
<li class="flex items-start gap-3"><span class="step-num mt-0.5">3</span> <span>Enter your <strong>Username or Email</strong> and <strong>Password</strong>.</span></li>
<li class="flex items-start gap-3"><span class="step-num mt-0.5">4</span> <span>Click <strong>"Sign In"</strong> — you will be redirected to the Admin Dashboard.</span></li>
</ul>
</div>

<div class="section-card bg-white/5 rounded-xl p-6 border border-white/10">
<h3 class="text-lg font-semibold mb-3">3. Login as Staff</h3>
<p class="text-sm text-slate-400 leading-relaxed mb-4">Staff members need a Business Code to log in:</p>
<ul class="space-y-2 text-sm text-slate-400">
<li class="flex items-start gap-3"><span class="step-num mt-0.5">1</span> <span>Click <strong>"Login"</strong> from the homepage.</span></li>
<li class="flex items-start gap-3"><span class="step-num mt-0.5">2</span> <span>Select the <strong>"Staff"</strong> role tab.</span></li>
<li class="flex items-start gap-3"><span class="step-num mt-0.5">3</span> <span>Enter the <strong>Business Code</strong> provided by your admin.</span></li>
<li class="flex items-start gap-3"><span class="step-num mt-0.5">4</span> <span>Enter your <strong>Username</strong> and <strong>Password</strong>.</span></li>
<li class="flex items-start gap-3"><span class="step-num mt-0.5">5</span> <span>Click <strong>"Sign In"</strong> — you will be redirected to the Staff Dashboard.</span></li>
</ul>
<div class="mt-4 p-3 rounded-lg bg-amber-500/10 border border-amber-500/20 text-sm text-amber-400">
<strong>Note:</strong> Staff accounts must be created by an Admin first. You cannot self-register as staff.
</div>
</div>

</div>
</section>

<!-- ─── ADMIN GUIDE ─── -->
<section id="admin-guide">
<h2 class="text-2xl font-bold mb-6">Admin Guide</h2>
<p class="text-slate-400 leading-relaxed mb-8">As an Admin, you have full control over your business workspace. The dashboard is organized into sections accessible from the navigation bar.</p>

<div class="space-y-8">

<!-- Dashboard -->
<div id="admin-dashboard" class="section-card bg-white/5 rounded-xl p-6 border border-white/10">
<h3 class="text-lg font-semibold mb-3">Dashboard</h3>
<p class="text-sm text-slate-400 leading-relaxed mb-4">The Dashboard is your main landing page after login. It shows:</p>
<ul class="space-y-2 text-sm text-slate-400">
<li><strong>Statistics Cards</strong> — Total staff, total tasks, pending tasks, in-progress tasks, and completed tasks. These update automatically.</li>
<li><strong>Task Assignment Form</strong> — Quickly create and assign new tasks to staff members.</li>
<li><strong>Task Tracker</strong> — A complete table of all tasks with filters for status, staff, priority, and date range.</li>
</ul>
<p class="text-sm text-slate-400 mt-3">The dashboard auto-refreshes every 10 seconds when changes are detected, so you always see the latest data.</p>
</div>

<!-- Staff Management -->
<div id="admin-staff" class="section-card bg-white/5 rounded-xl p-6 border border-white/10">
<h3 class="text-lg font-semibold mb-3">Staff Management</h3>
<p class="text-sm text-slate-400 leading-relaxed mb-4">Manage all your team members from this section.</p>

<h4 class="font-semibold text-sm mt-4 mb-2 text-purple-400">Adding Staff</h4>
<ol class="list-decimal list-inside space-y-1 text-sm text-slate-400 ml-2">
<li>Click <strong>"+ New Staff"</strong> to open the form.</li>
<li>Enter <strong>Name</strong>, <strong>Username</strong>, <strong>Password</strong> (min 8 chars), and <strong>Department</strong>.</li>
<li>Click <strong>"Create"</strong> — the staff member is added and can now log in.</li>
</ol>

<h4 class="font-semibold text-sm mt-4 mb-2 text-purple-400">Editing Staff</h4>
<p class="text-sm text-slate-400">Click the <strong>"Edit"</strong> button next to any staff member. You can update their name, username, department, and status. Password is optional — leave blank to keep the existing password.</p>

<h4 class="font-semibold text-sm mt-4 mb-2 text-purple-400">Profile Photo</h4>
<p class="text-sm text-slate-400">Staff members can upload their own profile photo from their dashboard. Admins can delete a staff member's photo by clicking <strong>"Delete Photo"</strong>.</p>

<h4 class="font-semibold text-sm mt-4 mb-2 text-purple-400">Activate / Deactivate</h4>
<p class="text-sm text-slate-400">Toggle a staff member's status between active and inactive. Inactive staff cannot log in.</p>

<h4 class="font-semibold text-sm mt-4 mb-2 text-purple-400">Deleting Staff</h4>
<p class="text-sm text-slate-400">Click <strong>"Delete"</strong> to permanently remove a staff member and all their associated tasks. This action requires confirmation and cannot be undone.</p>
</div>

<!-- Departments -->
<div id="admin-departments" class="section-card bg-white/5 rounded-xl p-6 border border-white/10">
<h3 class="text-lg font-semibold mb-3">Department Management</h3>
<p class="text-sm text-slate-400 leading-relaxed mb-4">Organize your staff into departments for better management.</p>
<ul class="space-y-2 text-sm text-slate-400">
<li><strong>Adding Departments</strong> — Enter a department name and click "Add". The name "Management" is reserved and cannot be used.</li>
<li><strong>Editing Departments</strong> — Click "Edit" next to a department to rename it.</li>
<li><strong>Deleting Departments</strong> — Click "Delete" to remove a department. Staff assigned to the deleted department will have their department field cleared.</li>
</ul>
</div>

<!-- Task Management -->
<div id="admin-tasks" class="section-card bg-white/5 rounded-xl p-6 border border-white/10">
<h3 class="text-lg font-semibold mb-3">Task Management</h3>
<p class="text-sm text-slate-400 leading-relaxed mb-4">Create, assign, and track tasks for your staff.</p>

<h4 class="font-semibold text-sm mt-4 mb-2 text-purple-400">Creating Tasks</h4>
<ol class="list-decimal list-inside space-y-1 text-sm text-slate-400 ml-2">
<li>Fill in the <strong>Task Title</strong> (required).</li>
<li>Select the <strong>Assigned Staff</strong> member from the dropdown.</li>
<li>Choose <strong>Priority</strong> — Low, Medium, High, or Urgent.</li>
<li>Add a <strong>Description</strong> with details about the task.</li>
<li>Set a <strong>Due Date</strong> and optional <strong>Due Time</strong>.</li>
<li>Click <strong>"Assign Task"</strong> — the task appears in the staff member's dashboard immediately.</li>
</ol>

<h4 class="font-semibold text-sm mt-4 mb-2 text-purple-400">Task Statuses</h4>
<div class="grid grid-cols-3 gap-3 text-sm">
<div class="bg-amber-500/10 rounded-lg p-3 border border-amber-500/20"><span class="text-amber-400 font-medium">To-Do</span><p class="text-xs text-slate-500 mt-1">Task assigned, not started</p></div>
<div class="bg-blue-500/10 rounded-lg p-3 border border-blue-500/20"><span class="text-blue-400 font-medium">In Progress</span><p class="text-xs text-slate-500 mt-1">Staff is working on it</p></div>
<div class="bg-emerald-500/10 rounded-lg p-3 border border-emerald-500/20"><span class="text-emerald-400 font-medium">Completed</span><p class="text-xs text-slate-500 mt-1">Task finished</p></div>
</div>

<h4 class="font-semibold text-sm mt-4 mb-2 text-purple-400">Live Task Progress</h4>
<p class="text-sm text-slate-400">For tasks that are "In Progress", click the <strong>"Live"</strong> button to open a real-time chat interface. You can see progress updates from staff instantly and reply — the conversation updates in real-time like WhatsApp using Server-Sent Events (SSE). Press <kbd>Enter</kbd> to send, <kbd>Shift</kbd>+<kbd>Enter</kbd> for a new line. A purple pulse indicator on the task row shows when a new message from staff arrives.</p>

<h4 class="font-semibold text-sm mt-4 mb-2 text-purple-400">Editing Tasks</h4>
<p class="text-sm text-slate-400">Click the <strong>"Edit"</strong> button in the task tracker to open the edit modal. You can modify the title, assigned staff, priority, status, due date, and description.</p>

<h4 class="font-semibold text-sm mt-4 mb-2 text-purple-400">Deleting Tasks</h4>
<p class="text-sm text-slate-400">Click <strong>"Delete"</strong> to permanently remove a task. This action requires confirmation.</p>

<h4 class="font-semibold text-sm mt-4 mb-2 text-purple-400">Filters</h4>
<p class="text-sm text-slate-400">Use the filter buttons above the task table to view tasks by status (All, To-Do, In Progress, Completed). You can also filter by staff member, priority, and date range for precise task tracking.</p>

<h4 class="font-semibold text-sm mt-4 mb-2 text-purple-400">Priority Levels</h4>
<p class="text-sm text-slate-400">Tasks are color-coded by priority: <span class="text-slate-400">Low</span> (gray), <span class="text-blue-400">Medium</span> (blue), <span class="text-orange-400">High</span> (orange), and <span class="text-red-400">Urgent</span> (red). Tasks are automatically sorted with urgent tasks appearing first.</p>
</div>

<!-- Activity Tracking -->
<div id="admin-activity" class="section-card bg-white/5 rounded-xl p-6 border border-white/10">
<h3 class="text-lg font-semibold mb-3">Activity Tracking</h3>
<p class="text-sm text-slate-400 leading-relaxed mb-4">Monitor what your staff are working on in real-time.</p>
<ul class="space-y-2 text-sm text-slate-400">
<li><strong>Work Logs</strong> — Staff members log their daily work with descriptions and dates. All logs appear in this section.</li>
<li><strong>Filters</strong> — Filter by staff member and date range to find specific entries.</li>
<li><strong>Reply System</strong> — Click <strong>"Reply"</strong> on any work log entry to open a conversation thread. This allows you to ask questions, provide feedback, or discuss the work entry with your staff member.</li>
<li><strong>New Activity Badge</strong> — When a staff member logs new work or replies, a badge indicator appears on the "Staff Activity" navigation button so you never miss updates.</li>
</ul>
</div>

<!-- Profile & Business Profile -->
<div id="admin-profile" class="section-card bg-white/5 rounded-xl p-6 border border-white/10">
<h3 class="text-lg font-semibold mb-3">Profile &amp; Business Profile</h3>
<p class="text-sm text-slate-400 leading-relaxed mb-4">Your admin profile is accessible from the top-right corner profile icon.</p>

<h4 class="font-semibold text-sm mt-4 mb-2 text-purple-400">Profile Dropdown</h4>
<ul class="space-y-2 text-sm text-slate-400">
<li><strong>Profile Photo</strong> — Click your profile picture (or initial badge) to open the dropdown. Click the camera icon on the photo to upload or change your profile picture with crop support.</li>
<li><strong>Account Details</strong> — View your name, username, business name, role, status, and business code.</li>
<li><strong>Business Profile</strong> — Click "View Business Profile" from the dropdown to see your registered business information including business name, code, owner details, and address.</li>
<li><strong>Logout</strong> — Click "Logout" to end your session.</li>
</ul>

<h4 class="font-semibold text-sm mt-4 mb-2 text-purple-400">Business Information</h4>
<ul class="space-y-2 text-sm text-slate-400">
<li><strong>Business Name</strong> — The name you registered with.</li>
<li><strong>Business Code</strong> — Your unique code for staff login. Share this with your team.</li>
<li><strong>Owner Details</strong> — Owner name, email, phone, and address.</li>
</ul>
<div class="mt-4 p-3 rounded-lg bg-amber-500/10 border border-amber-500/20 text-sm text-amber-400">
<strong>Note:</strong> Business profile details cannot be edited. If you need to change any information, please contact TaskHub support.
</div>
</div>

</div>
</section>

<!-- ─── STAFF GUIDE ─── -->
<section id="staff-guide">
<h2 class="text-2xl font-bold mb-6">Staff Guide</h2>
<p class="text-slate-400 leading-relaxed mb-8">As a Staff member, you have access to your assigned tasks, work logging, and communication tools.</p>

<div class="space-y-8">

<!-- Tasks -->
<div id="staff-tasks" class="section-card bg-white/5 rounded-xl p-6 border border-white/10">
<h3 class="text-lg font-semibold mb-3">Managing Your Tasks</h3>
<p class="text-sm text-slate-400 leading-relaxed mb-4">Your tasks are organized into three tabs: <strong>To-Do</strong>, <strong>In Progress</strong>, and <strong>Completed</strong>.</p>

<h4 class="font-semibold text-sm mt-4 mb-2 text-purple-400">Starting a Task</h4>
<ol class="list-decimal list-inside space-y-1 text-sm text-slate-400 ml-2">
<li>Navigate to the <strong>"To-Do"</strong> tab to see your pending tasks.</li>
<li>Click <strong>"Start Task"</strong> on any task — it moves to "In Progress".</li>
<li>The task now appears under the <strong>"In Progress"</strong> tab.</li>
</ol>

<h4 class="font-semibold text-sm mt-4 mb-2 text-purple-400">Completing a Task</h4>
<ol class="list-decimal list-inside space-y-1 text-sm text-slate-400 ml-2">
<li>Switch to the <strong>"In Progress"</strong> tab.</li>
<li>Optionally, add <strong>Work Notes</strong> in the text area describing what was done.</li>
<li>Click <strong>"Mark Completed"</strong> — a confirmation dialog will appear.</li>
<li>Confirm to complete the task. It moves to the <strong>"Completed"</strong> tab.</li>
</ol>

<h4 class="font-semibold text-sm mt-4 mb-2 text-purple-400">Undo / Move Task</h4>
<p class="text-sm text-slate-400">If you moved a task to "In Progress" by mistake, click the undo button (<svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>) to move it back to "To-Do". Similarly, completed tasks can be moved back to "In Progress".</p>

<h4 class="font-semibold text-sm mt-4 mb-2 text-purple-400">Task Information</h4>
<p class="text-sm text-slate-400">Each task card shows: title, priority level (color-coded), description (if any), due date with overdue indicator, your department, and any work notes left by the admin.</p>
</div>

<!-- Work Logs -->
<div id="staff-worklog" class="section-card bg-white/5 rounded-xl p-6 border border-white/10">
<h3 class="text-lg font-semibold mb-3">Work Logs</h3>
<p class="text-sm text-slate-400 leading-relaxed mb-4">Log your daily work activities to keep your admin informed.</p>

<h4 class="font-semibold text-sm mt-4 mb-2 text-purple-400">Logging Work</h4>
<ol class="list-decimal list-inside space-y-1 text-sm text-slate-400 ml-2">
<li>Click the <strong>"+ Log Work"</strong> button in the top navigation.</li>
<li>Describe the work you did in the text area (required).</li>
<li>Select the <strong>Date</strong> (defaults to today).</li>
<li>Click <strong>"Save"</strong> — your log entry is submitted and visible to the admin.</li>
</ol>

<h4 class="font-semibold text-sm mt-4 mb-2 text-purple-400">Viewing Your Logs</h4>
<p class="text-sm text-slate-400">Click <strong>"My Logs"</strong> to see all your work log entries. You can filter by date range to find specific entries. Each entry shows the description, date, and timestamp. If the admin has replied, you'll see a reply count.</p>
</div>

<!-- Conversations -->
<div id="staff-replies" class="section-card bg-white/5 rounded-xl p-6 border border-white/10">
<h3 class="text-lg font-semibold mb-3">Conversations</h3>
<p class="text-sm text-slate-400 leading-relaxed mb-4">Communicate with your admin about specific work log entries through the reply system.</p>
<ul class="space-y-2 text-sm text-slate-400">
<li><strong>Opening a Conversation</strong> — From "My Logs", click <strong>"View"</strong> or <strong>"Reply"</strong> on any entry to open the conversation thread.</li>
<li><strong>Sending a Reply</strong> — Type your message and click <strong>"Send"</strong>, or press <kbd>Enter</kbd> (use <kbd>Shift</kbd>+<kbd>Enter</kbd> for a new line).</li>
<li><strong>Auto-Refresh</strong> — When the conversation modal is open, new replies from the admin appear automatically every 2 seconds.</li>
<li><strong>Scroll to Bottom</strong> — If you're near the bottom of the conversation, new messages auto-scroll down.</li>
</ul>
</div>

<!-- Profile -->
<div id="staff-profile" class="section-card bg-white/5 rounded-xl p-6 border border-white/10">
<h3 class="text-lg font-semibold mb-3">Your Profile</h3>
<p class="text-sm text-slate-400 leading-relaxed mb-4">Click your profile picture (or initial icon) in the top-right corner to open the profile dropdown. You can see:</p>
<ul class="space-y-2 text-sm text-slate-400">
<li>Your <strong>Name</strong>, <strong>Username</strong>, and <strong>Business Name</strong>.</li>
<li>Your <strong>Department</strong> and <strong>Account Status</strong>.</li>
<li><strong>Member Since</strong> date.</li>
<li><strong>Change Photo</strong> — Click the camera icon on your profile picture to upload a new photo. You can crop and resize before saving.</li>
<li><strong>Logout</strong> — Click "Logout" to end your session.</li>
</ul>
</div>

</div>
</section>

<!-- ─── FEATURES OVERVIEW ─── -->
<section id="features">
<h2 class="text-2xl font-bold mb-6">Features Overview</h2>
<div class="grid sm:grid-cols-2 gap-4">
<div class="section-card bg-white/5 rounded-xl p-5 border border-white/10">
<div class="flex items-start gap-3">
<div class="w-10 h-10 rounded-lg bg-purple-600/20 flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
<div><h3 class="font-semibold text-sm">Task Management</h3><p class="text-xs text-slate-500 mt-1">Full CRUD with priority levels, due dates, status tracking, and real-time updates.</p></div>
</div>
</div>
<div class="section-card bg-white/5 rounded-xl p-5 border border-white/10">
<div class="flex items-start gap-3">
<div class="w-10 h-10 rounded-lg bg-blue-600/20 flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/></svg></div>
<div><h3 class="font-semibold text-sm">Staff Management</h3><p class="text-xs text-slate-500 mt-1">Add, edit, activate/deactivate, and delete staff members with photo management.</p></div>
</div>
</div>
<div class="section-card bg-white/5 rounded-xl p-5 border border-white/10">
<div class="flex items-start gap-3">
<div class="w-10 h-10 rounded-lg bg-emerald-600/20 flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></div>
<div><h3 class="font-semibold text-sm">Activity Tracking</h3><p class="text-xs text-slate-500 mt-1">Staff work logs with date filters, reply threads, and real-time notifications.</p></div>
</div>
</div>
<div class="section-card bg-white/5 rounded-xl p-5 border border-white/10">
<div class="flex items-start gap-3">
<div class="w-10 h-10 rounded-lg bg-indigo-600/20 flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
<div><h3 class="font-semibold text-sm">Department Management</h3><p class="text-xs text-slate-500 mt-1">Organize staff into departments for better structure and reporting.</p></div>
</div>
</div>
<div class="section-card bg-white/5 rounded-xl p-5 border border-white/10">
<div class="flex items-start gap-3">
<div class="w-10 h-10 rounded-lg bg-cyan-600/20 flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></div>
<div><h3 class="font-semibold text-sm">Real-Time Updates</h3><p class="text-xs text-slate-500 mt-1">Real-time task progress chat with SSE (Server-Sent Events) and instant polling — like WhatsApp.</p></div>
</div>
</div>
<div class="section-card bg-white/5 rounded-xl p-5 border border-white/10">
<div class="flex items-start gap-3">
<div class="w-10 h-10 rounded-lg bg-orange-600/20 flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></div>
<div><h3 class="font-semibold text-sm">Secure Authentication</h3><p class="text-xs text-slate-500 mt-1">Session-based auth with token verification, session timeout, and role-based access control.</p></div>
</div>
</div>
<div class="section-card bg-white/5 rounded-xl p-5 border border-white/10">
<div class="flex items-start gap-3">
<div class="w-10 h-10 rounded-lg bg-pink-600/20 flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
<div><h3 class="font-semibold text-sm">File Uploads</h3><p class="text-xs text-slate-500 mt-1">Staff profile photo upload with crop and resize functionality via Cropper.js.</p></div>
</div>
</div>
<div class="section-card bg-white/5 rounded-xl p-5 border border-white/10">
<div class="flex items-start gap-3">
<div class="w-10 h-10 rounded-lg bg-teal-600/20 flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></div>
<div><h3 class="font-semibold text-sm">Data Privacy</h3><p class="text-xs text-slate-500 mt-1">Business data isolation ensures each business's data is completely separate and secure.</p></div>
</div>
</div>
<div class="section-card bg-white/5 rounded-xl p-5 border border-white/10">
<div class="flex items-start gap-3">
<div class="w-10 h-10 rounded-lg bg-red-600/20 flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg></div>
<div><h3 class="font-semibold text-sm">Theme Support</h3><p class="text-xs text-slate-500 mt-1">Dark and light mode with persistent theme preference across sessions.</p></div>
</div>
</div>
</div>
</section>

<!-- ─── FAQ ─── -->
<section id="faq">
<h2 class="text-2xl font-bold mb-6">Frequently Asked Questions</h2>
<div class="space-y-4">

<div class="section-card bg-white/5 rounded-xl p-5 border border-white/10">
<h3 class="font-semibold text-sm mb-2">How do I get my Business Code?</h3>
<p class="text-sm text-slate-400">Your Business Code is generated automatically when you register your business. You can find it in the Admin Dashboard under <strong>Business Profile</strong> section.</p>
</div>

<div class="section-card bg-white/5 rounded-xl p-5 border border-white/10">
<h3 class="font-semibold text-sm mb-2">A staff member forgot their password. What do I do?</h3>
<p class="text-sm text-slate-400">As an Admin, go to <strong>Staff Management</strong>, click <strong>"Edit"</strong> on the staff member, enter a new password, and save. The staff member can then log in with the new password.</p>
</div>

<div class="section-card bg-white/5 rounded-xl p-5 border border-white/10">
<h3 class="font-semibold text-sm mb-2">Can I edit my business information after registration?</h3>
<p class="text-sm text-slate-400">No, business profile details cannot be edited after registration. If you need to update your business information, please contact TaskHub support at <a href="mailto:akashgaud7389@gmail.com" class="text-purple-400 hover:text-purple-300">akashgaud7389@gmail.com</a>.</p>
</div>

<div class="section-card bg-white/5 rounded-xl p-5 border border-white/10">
<h3 class="font-semibold text-sm mb-2">Why can't a staff member log in?</h3>
<p class="text-sm text-slate-400">Check the following: (1) They are using the correct Business Code. (2) Their account status is <strong>"Active"</strong> in Staff Management. (3) They are using the correct username and password. If the issue persists, reset their password from Staff Management.</p>
</div>

<div class="section-card bg-white/5 rounded-xl p-5 border border-white/10">
<h3 class="font-semibold text-sm mb-2">How do I delete a staff member?</h3>
<p class="text-sm text-slate-400">Go to <strong>Staff Management</strong>, click <strong>"Delete"</strong> next to the staff member. Confirm the action. This permanently removes the staff member and all their associated tasks.</p>
</div>

<div class="section-card bg-white/5 rounded-xl p-5 border border-white/10">
<h3 class="font-semibold text-sm mb-2">Is my data secure?</h3>
<p class="text-sm text-slate-400">Yes. Each business operates in a completely isolated environment. Data is stored in a secure PostgreSQL database hosted on Supabase with encryption at rest and in transit. Session-based authentication with token verification protects against unauthorized access.</p>
</div>

<div class="section-card bg-white/5 rounded-xl p-5 border border-white/10">
<h3 class="font-semibold text-sm mb-2">How do I change the theme?</h3>
<p class="text-sm text-slate-400">Click the theme toggle button (sun/moon icon) in the top navigation bar of the admin or staff dashboard. Your preference is saved and will persist across sessions.</p>
</div>

<div class="section-card bg-white/5 rounded-xl p-5 border border-white/10">
<h3 class="font-semibold text-sm mb-2">What happens if my session expires?</h3>
<p class="text-sm text-slate-400">If your session expires, you will be automatically redirected to the login page. Simply log in again to continue. The system checks your session validity every 30 seconds.</p>
</div>

<div class="section-card bg-white/5 rounded-xl p-5 border border-white/10">
<h3 class="font-semibold text-sm mb-2">How do I contact support?</h3>
<p class="text-sm text-slate-400">You can reach out via the <a href="contact.php" class="text-purple-400 hover:text-purple-300">Contact page</a> or email directly at <a href="mailto:akashgaud7389@gmail.com" class="text-purple-400 hover:text-purple-300">akashgaud7389@gmail.com</a>.</p>
</div>

<div class="section-card bg-white/5 rounded-xl p-5 border border-white/10 border-amber-500/20 bg-amber-500/5">
<h3 class="font-semibold text-sm mb-2">Why is the tool slow? / Performance Requirements</h3>
<p class="text-sm text-slate-400">TaskHub is a cloud-based application that communicates with a remote database server. For the best experience, a <strong>stable internet connection</strong> with at least <strong>2–3 Mbps</strong> speed is recommended. If the tool feels slow, check your internet speed, avoid large file uploads on slow connections, and ensure no VPN or firewall is blocking API requests to the Supabase backend.</p>
</div>

</div>
</section>

</main>
</div>
</div>

<!-- ═══ FOOTER ═══ -->
<footer class="border-t border-white/5 bg-white/[.02] py-12 mt-16">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
<div><div class="flex items-center gap-2 mb-4"><div class="w-6 h-6 rounded bg-purple-600 flex items-center justify-center text-xs font-bold">T</div><span class="font-bold"><span class="text-purple-400">Task</span>Hub</span></div><p class="text-sm text-slate-500 mb-2">Streamline your workflow, manage tasks, and grow your business.</p><p class="text-xs text-slate-500">Task Management &amp; Workflow Automation System</p></div>
<div><h4 class="font-semibold mb-4 text-sm uppercase tracking-wider text-slate-400">Product</h4><ul class="space-y-2 text-sm text-slate-500"><li><a href="features.php" class="hover:text-white transition">Features</a></li><li><a href="usermanual.php" class="hover:text-white transition">User Manual</a></li><li><a href="register.php" class="hover:text-white transition">Register</a></li></ul></div>
<div><h4 class="font-semibold mb-4 text-sm uppercase tracking-wider text-slate-400">Company</h4><ul class="space-y-2 text-sm text-slate-500"><li><a href="about.php" class="hover:text-white transition">About Us</a></li><li><a href="contact.php" class="hover:text-white transition">Contact</a></li></ul></div>
<div><h4 class="font-semibold mb-4 text-sm uppercase tracking-wider text-slate-400">Legal</h4><ul class="space-y-2 text-sm text-slate-500"><li><a href="privacy.php" class="hover:text-white transition">Privacy Policy</a></li><li><a href="terms.php" class="hover:text-white transition">Terms of Service</a></li></ul></div>
</div>
<div class="border-t border-white/5 pt-8 text-center text-xs text-slate-600">
TaskHub &mdash; Developed by <span class="text-purple-400">Aakash Gaud</span> (<button onclick="var b=this;var l=this.nextElementSibling;b.classList.add('hidden');l.classList.remove('hidden');setTimeout(function(){l.classList.add('hidden');b.classList.remove('hidden')},5000)" class="text-purple-400 hover:text-purple-300 bg-purple-500/10 px-1.5 py-0.5 rounded text-xs font-medium inline-flex items-center gap-1">Show Email</button><a href="mailto:akashgaud7389@gmail.com" class="text-purple-400 hover:text-purple-300 hidden">akashgaud7389@gmail.com</a>) &copy; 2026 All Rights Reserved
</div>
</div>
</footer>

</body>
</html>