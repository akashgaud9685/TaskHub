<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TaskHub — Task Management &amp; Workflow Automation</title>
<meta name="description" content="TaskHub is a powerful task management and workflow automation system for businesses. Manage tasks, track staff activity, and boost productivity.">
<meta name="keywords" content="task management, workflow automation, staff management, business productivity">
<link rel="icon" type="image/x-icon" href="favicon.ico">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{font-family:'Inter',sans-serif}
html{scroll-behavior:smooth}
.hero-glow{position:absolute;width:600px;height:600px;border-radius:50%;filter:blur(120px);pointer-events:none;opacity:.3;animation:pulse-glow 4s ease-in-out infinite}
@keyframes pulse-glow{0%,100%{opacity:.2;transform:scale(1)}50%{opacity:.35;transform:scale(1.1)}}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-20px)}}
@keyframes fade-up{from{opacity:0;transform:translateY(40px)}to{opacity:1;transform:translateY(0)}}
.animate-float{animation:float 6s ease-in-out infinite}
.animate-fade-up{animation:fade-up .8s ease-out forwards}
.stagger-1{animation-delay:.1s}.stagger-2{animation-delay:.2s}.stagger-3{animation-delay:.3s}.stagger-4{animation-delay:.4s}.stagger-5{animation-delay:.5s}
.login-modal{transition:opacity .3s ease,visibility .3s ease}.login-modal.active{opacity:1;visibility:visible}
.login-modal .modal-content{transform:scale(.95);transition:transform .3s ease}
.login-modal.active .modal-content{transform:scale(1)}
.dash-card{transition:all .4s ease}
.dash-card:hover{transform:translateY(-8px);box-shadow:0 20px 60px rgba(147,51,234,.15)}
</style>
</head>
<body class="bg-slate-900 text-white antialiased">

<!-- ═══ NAVBAR ═══ -->
<nav class="fixed top-0 w-full z-50 bg-slate-900/80 backdrop-blur-xl border-b border-white/5">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex items-center justify-between h-16">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-lg bg-purple-600 flex items-center justify-center text-sm font-bold">T</div>
<span class="text-xl font-bold"><span class="text-purple-400">Task</span>Hub</span>
</div>
<div class="hidden md:flex items-center gap-6">
<a href="features.php" class="text-sm text-slate-300 hover:text-white transition">Features</a>
<a href="about.php" class="text-sm text-slate-300 hover:text-white transition">About</a>
<a href="contact.php" class="text-sm text-slate-300 hover:text-white transition">Contact</a>
<a href="usermanual.php" class="text-sm text-slate-300 hover:text-white transition">Manual</a>
</div>
<div class="flex items-center gap-3">
<button onclick="toggleRegister()" class="text-sm text-slate-300 hover:text-white transition hidden sm:inline">Register</button>
<button onclick="toggleLogin()" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">Login</button>
</div>
</div>
</div>
</nav>

<!-- ═══ HERO ═══ -->
<section class="relative min-h-screen flex items-center overflow-hidden pt-16">
<div class="hero-glow bg-purple-600" style="top:-100px;right:-200px"></div>
<div class="hero-glow bg-blue-600" style="bottom:-100px;left:-200px;animation-delay:2s"></div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10">
<div class="grid lg:grid-cols-2 gap-12 items-center">
<div>
<div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-400 text-sm mb-6 animate-fade-up">
<span class="w-2 h-2 rounded-full bg-purple-400 animate-pulse"></span>
Streamline Your Workflow
</div>
<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight mb-6 animate-fade-up stagger-1">
Manage Tasks,<br>
<span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-blue-400">Track Progress</span><br>
Grow Your Business
</h1>
<p class="text-lg text-slate-400 mb-8 max-w-xl animate-fade-up stagger-2">
TaskHub is a powerful task management and workflow automation platform. Assign tasks, monitor staff activity, and keep your business running smoothly.
</p>
<div class="flex flex-wrap gap-4 animate-fade-up stagger-3">
<a href="features.php" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition">Explore Features</a>
<button onclick="toggleLogin()" class="px-6 py-3 border border-slate-600 hover:border-purple-500 text-slate-300 hover:text-white font-medium rounded-lg transition">Get Started</button>
</div>
<div class="flex items-center gap-6 mt-10 text-sm text-slate-500 animate-fade-up stagger-4">
<div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Fast and secure</div>
<div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Free to start</div>
<div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Easy to understand</div>
</div>
</div>
<div class="relative animate-fade-up stagger-3">
<div class="relative rounded-2xl overflow-hidden border border-white/10 bg-gradient-to-br from-purple-900/30 to-slate-800/30 p-2">
<div class="rounded-xl overflow-hidden">
<img src="assets/images/dashboard-preview.svg" alt="TaskHub Dashboard Preview" class="w-full">
</div>
</div>
<div class="absolute -bottom-6 -left-6 w-32 h-32 rounded-2xl bg-purple-600/20 border border-purple-500/30 backdrop-blur-xl flex items-center justify-center animate-float">
<div class="text-center"><p class="text-2xl font-bold text-purple-400">10+</p><p class="text-xs text-slate-400">Features</p></div>
</div>
<div class="absolute -top-4 -right-4 w-28 h-28 rounded-2xl bg-emerald-600/20 border border-emerald-500/30 backdrop-blur-xl flex items-center justify-center animate-float" style="animation-delay:3s">
<div class="text-center"><p class="text-2xl font-bold text-emerald-400">99%</p><p class="text-xs text-slate-400">Uptime</p></div>
</div>
</div>
</div>
</div>
</section>

<!-- ═══ FEATURES GRID (SHORT) ═══ -->
<section class="py-20 border-t border-white/5 bg-white/[.02]">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="text-center mb-12">
<h2 class="text-3xl sm:text-4xl font-bold mb-4">Why Choose TaskHub?</h2>
<p class="text-slate-400 max-w-2xl mx-auto">Everything you need to manage your team and tasks effectively.</p>
</div>
<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
<div class="dash-card bg-white/5 rounded-2xl p-6 border border-white/10 text-center">
<div class="w-12 h-12 rounded-xl bg-purple-600/20 flex items-center justify-center mx-auto mb-4"><svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
<h3 class="font-semibold mb-1">Task Management</h3>
<p class="text-xs text-slate-400">Create, assign, prioritize, and track tasks with real-time updates and status workflow.</p>
</div>
<div class="dash-card bg-white/5 rounded-2xl p-6 border border-white/10 text-center">
<div class="w-12 h-12 rounded-xl bg-blue-600/20 flex items-center justify-center mx-auto mb-4"><svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/></svg></div>
<h3 class="font-semibold mb-1">Staff &amp; Department Management</h3>
<p class="text-xs text-slate-400">Manage your team with departments, photo uploads, and role-based access control.</p>
</div>
<div class="dash-card bg-white/5 rounded-2xl p-6 border border-white/10 text-center">
<div class="w-12 h-12 rounded-xl bg-emerald-600/20 flex items-center justify-center mx-auto mb-4"><svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></div>
<h3 class="font-semibold mb-1">Activity &amp; Work Logs</h3>
<p class="text-xs text-slate-400">Staff log daily work, admin reviews with threaded replies and real-time notifications.</p>
</div>
</div>
<div class="mt-8 p-4 rounded-xl bg-amber-500/5 border border-amber-500/20 text-xs text-slate-400 flex items-start gap-3">
<svg class="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
<span>TaskHub is a cloud-based application. For optimal performance, a <strong>stable internet connection with 2-3 Mbps speed</strong> is recommended. Slower connections may affect real-time updates and file uploads.</span>
</div>
<div class="text-center mt-10">
<a href="features.php" class="inline-flex items-center gap-2 text-purple-400 hover:text-purple-300 font-medium transition">View All Features <span>&rarr;</span></a>
</div>
</div>
</section>

<!-- ═══ STATS BANNER ═══ -->
<section class="py-16 border-t border-white/5">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
<div><p class="text-4xl font-bold text-purple-400">9+</p><p class="text-sm text-slate-500 mt-1">Powerful Features</p></div>
<div><p class="text-4xl font-bold text-blue-400">100+</p><p class="text-sm text-slate-500 mt-1">Active Businesses</p></div>
<div><p class="text-4xl font-bold text-emerald-400">1K+</p><p class="text-sm text-slate-500 mt-1">Tasks Managed</p></div>
<div><p class="text-4xl font-bold text-amber-400">99.9%</p><p class="text-sm text-slate-500 mt-1">Uptime</p></div>
</div>
</div>
</section>

<!-- ═══ FOOTER ═══ -->
<footer class="border-t border-white/5 bg-white/[.02] py-12">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
<div>
<div class="flex items-center gap-2 mb-4"><div class="w-6 h-6 rounded bg-purple-600 flex items-center justify-center text-xs font-bold">T</div><span class="font-bold"><span class="text-purple-400">Task</span>Hub</span></div>
<p class="text-sm text-slate-500 mb-2">Streamline your workflow, manage tasks, and grow your business.</p>
<p class="text-xs text-slate-500">Task Management &amp; Workflow Automation System</p>
</div>
<div><h4 class="font-semibold mb-4 text-sm uppercase tracking-wider text-slate-400">Product</h4><ul class="space-y-2 text-sm text-slate-500"><li><a href="features.php" class="hover:text-white transition">Features</a></li><li><a href="usermanual.php" class="hover:text-white transition">User Manual</a></li><li><button onclick="toggleRegister()" class="hover:text-white transition text-left">Register</button></li></ul></div>
<div><h4 class="font-semibold mb-4 text-sm uppercase tracking-wider text-slate-400">Company</h4><ul class="space-y-2 text-sm text-slate-500"><li><a href="about.php" class="hover:text-white transition">About Us</a></li><li><a href="contact.php" class="hover:text-white transition">Contact</a></li><li><button onclick="toggleLogin()" class="hover:text-white transition text-left">Login</button></li></ul></div>
<div><h4 class="font-semibold mb-4 text-sm uppercase tracking-wider text-slate-400">Legal</h4><ul class="space-y-2 text-sm text-slate-500"><li><a href="privacy.php" class="hover:text-white transition">Privacy Policy</a></li><li><a href="terms.php" class="hover:text-white transition">Terms of Service</a></li></ul></div>
</div>
<div class="border-t border-white/5 pt-8 text-center text-xs text-slate-600">
<p>TaskHub &mdash; Developed by <span class="text-purple-400">Aakash Gaud</span> (<button onclick="var b=this;var l=this.nextElementSibling;b.classList.add('hidden');l.classList.remove('hidden');setTimeout(function(){l.classList.add('hidden');b.classList.remove('hidden')},5000)" class="text-purple-400 hover:text-purple-300 bg-purple-500/10 px-1.5 py-0.5 rounded text-xs font-medium inline-flex items-center gap-1">Show Email</button><a href="mailto:akashgaud7389@gmail.com" class="text-purple-400 hover:text-purple-300 hidden">akashgaud7389@gmail.com</a>) &copy; 2026 All Rights Reserved</p>
</div>
</div>
</footer>

<!-- ═══ LOGIN MODAL ═══ -->
<div id="loginModal" class="login-modal fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 opacity-0 invisible">
<div class="modal-content w-full max-w-md">
<div class="bg-slate-800 rounded-2xl p-8 shadow-2xl border border-white/10">
<div class="flex items-center justify-between mb-6">
<h2 class="text-xl font-bold">Sign In</h2>
<button onclick="toggleLogin()" class="text-slate-400 hover:text-white transition text-2xl leading-none">&times;</button>
</div>
<div class="flex bg-white/5 rounded-lg p-1 mb-6">
<button type="button" onclick="setRole('admin')" id="roleAdmin" class="flex-1 py-2 text-sm font-medium rounded-md transition bg-purple-600 text-white">Admin</button>
<button type="button" onclick="setRole('staff')" id="roleStaff" class="flex-1 py-2 text-sm font-medium rounded-md transition text-slate-400 hover:text-white">Staff</button>
</div>
<h3 class="text-base font-semibold mb-4" id="loginHeading">Admin Login</h3>
<form id="loginForm" autocomplete="off">
<div id="businessCodeGroup" class="mb-4 hidden">
<label for="business_code" class="block text-sm font-medium text-slate-300 mb-1">Business Code</label>
<input type="text" id="business_code" name="business_code" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500">
</div>
<div class="mb-4">
<label for="username" class="block text-sm font-medium text-slate-300 mb-1">Username or Email</label>
<input type="text" id="username" name="username" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500">
</div>
<div class="mb-4">
<label for="password" class="block text-sm font-medium text-slate-300 mb-1">Password</label>
<div class="relative">
<input type="password" id="password" name="password" required class="w-full px-4 py-2.5 pr-10 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500">
<button type="button" onclick="togglePass(this)" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition p-1">
<svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
<svg class="w-5 h-5 eye-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1l22 22"/></svg>
</button>
</div>
</div>
<div id="loginError" class="mb-4 text-red-400 text-sm hidden"></div>
<button type="submit" id="loginBtn" class="w-full py-2.5 px-4 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
<span id="loginBtnText">Sign In</span>
<span id="loginSpinner" class="hidden inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin ml-2"></span>
</button>
<div class="mt-4 text-center"><button onclick="closeLogin();toggleRegister()" class="text-sm text-purple-400 hover:text-purple-300 transition">Register Business</button></div>
</form>
</div>
</div>
</div>

<!-- ═══ REGISTER MODAL ═══ -->
<div id="registerModal" class="login-modal fixed inset-0 z-[70] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 opacity-0 invisible">
<div class="modal-content w-full max-w-lg">
<div class="bg-slate-800 rounded-2xl p-6 shadow-2xl border border-white/10 max-h-[90vh] overflow-y-auto">
<div class="flex items-center justify-between mb-4">
<h2 class="text-lg font-bold">Register Business</h2>
<button onclick="toggleRegister()" class="text-slate-400 hover:text-white transition text-2xl leading-none">&times;</button>
</div>
<form id="registerForm" autocomplete="off">
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
<div>
<label for="reg_business_name" class="block text-xs font-medium text-slate-300 mb-0.5">Business Name <span class="text-red-400">*</span></label>
<input type="text" id="reg_business_name" name="business_name" required class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
</div>
<div>
<label for="reg_owner_name" class="block text-xs font-medium text-slate-300 mb-0.5">Owner Name <span class="text-red-400">*</span></label>
<input type="text" id="reg_owner_name" name="owner_name" required class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
</div>
<div>
<label for="reg_email" class="block text-xs font-medium text-slate-300 mb-0.5">Email <span class="text-red-400">*</span></label>
<input type="email" id="reg_email" name="email" required class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
</div>
<div>
<label for="reg_phone" class="block text-xs font-medium text-slate-300 mb-0.5">Phone</label>
<input type="tel" id="reg_phone" name="phone" pattern="[\d\s\-\+]+" inputmode="numeric" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode === 32 || event.charCode === 45 || event.charCode === 43" class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
</div>
<div>
<label for="reg_username" class="block text-xs font-medium text-slate-300 mb-0.5">Username <span class="text-red-400">*</span></label>
<input type="text" id="reg_username" name="username" required class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
</div>
<div>
<label for="reg_password" class="block text-xs font-medium text-slate-300 mb-0.5">Password <span class="text-red-400">*</span></label>
<div class="relative">
<input type="password" id="reg_password" name="password" required minlength="8" class="w-full px-3 py-2 pr-8 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
<button type="button" onclick="togglePass(this)" class="absolute right-1.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition">
<svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
<svg class="w-4 h-4 eye-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1l22 22"/></svg>
</button>
</div>
</div>
<div class="sm:col-span-2">
<label for="reg_address" class="block text-xs font-medium text-slate-300 mb-0.5">Address</label>
<textarea id="reg_address" name="address" rows="1" class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
</div>
</div>
<div id="registerError" class="text-red-400 text-xs hidden mt-2"></div>
<div class="mt-3 px-3 py-2 bg-amber-500/10 border border-amber-500/30 rounded-lg text-xs text-amber-400 leading-relaxed">
<strong>Note:</strong> Once registered, details cannot be edited.
</div>
<button type="submit" id="registerBtn" class="w-full mt-3 py-2 px-4 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
<span id="registerBtnText">Register Business</span>
<span id="registerSpinner" class="hidden inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin ml-2"></span>
</button>
<div class="mt-2 text-center"><button onclick="closeRegister();toggleLogin()" class="text-xs text-purple-400 hover:text-purple-300 transition">Already registered? Sign In</button></div>
</form>
</div>
</div>
</div>

<script>
function closeLogin() { document.getElementById('loginModal').classList.remove('active'); document.body.style.overflow = ''; }
function closeRegister() { document.getElementById('registerModal').classList.remove('active'); document.body.style.overflow = ''; }
function toggleLogin() {
const modal = document.getElementById('loginModal');
const isActive = !modal.classList.contains('active');
modal.classList.toggle('active');
document.body.style.overflow = isActive ? 'hidden' : '';
if (isActive) closeRegister();
}
function toggleRegister() {
const modal = document.getElementById('registerModal');
const isActive = !modal.classList.contains('active');
modal.classList.toggle('active');
document.body.style.overflow = isActive ? 'hidden' : '';
if (isActive) closeLogin();
}
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('login') === '1') toggleLogin();
if (urlParams.get('expired') === '1') {
setTimeout(() => {
const el = document.getElementById('loginError');
el.textContent = 'Session expired. Please login again.';
el.classList.remove('hidden');
}, 300);
}
let loginRole = 'admin';
function togglePass(btn) {
const inp = btn.parentElement.querySelector('input');
const isPw = inp.type === 'password';
inp.type = isPw ? 'text' : 'password';
btn.querySelectorAll('.eye-icon').forEach(el => el.classList.toggle('hidden'));
}
function setRole(role) {
loginRole = role;
document.getElementById('roleAdmin').className = 'flex-1 py-2 text-sm font-medium rounded-md transition ' + (role === 'admin' ? 'bg-purple-600 text-white' : 'text-slate-400 hover:text-white');
document.getElementById('roleStaff').className = 'flex-1 py-2 text-sm font-medium rounded-md transition ' + (role === 'staff' ? 'bg-purple-600 text-white' : 'text-slate-400 hover:text-white');
document.getElementById('loginHeading').textContent = role === 'admin' ? 'Admin Login' : 'Staff Login';
document.getElementById('businessCodeGroup').classList.toggle('hidden', role !== 'staff');
}
document.getElementById('loginForm').addEventListener('submit', async function(e) {
e.preventDefault();
const formData = new FormData(this);
formData.set('role', loginRole);
const errorEl = document.getElementById('loginError');
const btn = document.getElementById('loginBtn');
const btnText = document.getElementById('loginBtnText');
const spinner = document.getElementById('loginSpinner');
errorEl.classList.add('hidden');
btn.disabled = true;
btnText.textContent = 'Signing in...';
spinner.classList.remove('hidden');
try {
const res = await fetch('api/auth.php', {method:'POST', body:formData});
const data = await res.json();
if (data.success) { window.location.href = data.redirect; }
else { errorEl.textContent = data.message || 'Login failed'; errorEl.classList.remove('hidden'); btn.disabled = false; btnText.textContent = 'Sign In'; spinner.classList.add('hidden'); }
} catch(err) { errorEl.textContent = 'Network error. Please try again.'; errorEl.classList.remove('hidden'); btn.disabled = false; btnText.textContent = 'Sign In'; spinner.classList.add('hidden'); }
});
document.getElementById('registerForm').addEventListener('submit', async function(e) {
e.preventDefault();
const formData = new FormData(this);
const errEl = document.getElementById('registerError');
const btn = document.getElementById('registerBtn');
const text = document.getElementById('registerBtnText');
const spin = document.getElementById('registerSpinner');
errEl.classList.add('hidden');
btn.disabled = true; text.textContent = 'Registering...'; spin.classList.remove('hidden');
try {
const res = await fetch('api/business_register.php', { method: 'POST', body: formData });
const data = await res.json();
if (data.success) { window.location.href = data.redirect; }
else { errEl.textContent = data.message; errEl.classList.remove('hidden'); btn.disabled = false; text.textContent = 'Register Business'; spin.classList.add('hidden'); }
} catch(err) { errEl.textContent = 'Network error. Please try again.'; errEl.classList.remove('hidden'); btn.disabled = false; text.textContent = 'Register Business'; spin.classList.add('hidden'); }
});
</script>
</body>
</html>
