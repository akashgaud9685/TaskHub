<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Features — TaskHub</title>
<meta name="description" content="Explore all TaskHub features: task management, staff tracking, department management, real-time updates, and more.">
<link rel="icon" type="image/x-icon" href="favicon.ico">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{font-family:'Inter',sans-serif}
html{scroll-behavior:smooth}
@keyframes fade-up{from{opacity:0;transform:translateY(40px)}to{opacity:1;transform:translateY(0)}}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-12px)}}
.animate-fade-up{animation:fade-up .8s ease-out forwards}
.animate-float{animation:float 5s ease-in-out infinite}
.stagger-1{animation-delay:.1s}.stagger-2{animation-delay:.2s}.stagger-3{animation-delay:.3s}
.feature-card{transition:all .4s ease}
.feature-card:hover{transform:translateY(-8px);box-shadow:0 20px 60px rgba(147,51,234,.15)}
</style>
</head>
<body class="bg-slate-900 text-white antialiased">

<nav class="sticky top-0 z-50 bg-slate-900/80 backdrop-blur-xl border-b border-white/5">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
<a href="index.php" class="flex items-center gap-2"><div class="w-7 h-7 rounded bg-purple-600 flex items-center justify-center text-xs font-bold">T</div><span class="font-bold"><span class="text-purple-400">Task</span>Hub</span></a>
<div class="hidden md:flex items-center gap-6 text-sm">
<a href="index.php" class="text-slate-300 hover:text-white transition">Home</a>
<a href="about.php" class="text-slate-300 hover:text-white transition">About</a>
<a href="contact.php" class="text-slate-300 hover:text-white transition">Contact</a>
<a href="usermanual.php" class="text-slate-300 hover:text-white transition">Manual</a>
</div>
<div class="flex items-center gap-3">
<a href="register.php" class="text-sm text-slate-300 hover:text-white transition hidden sm:inline">Register</a>
<a href="index.php?login=1" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">Login</a>
</div>
</div>
</nav>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
<div class="text-center mb-16 animate-fade-up">
<div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-400 text-sm mb-4">
<span class="w-2 h-2 rounded-full bg-purple-400 animate-pulse"></span>
Everything You Need
</div>
<h1 class="text-4xl sm:text-5xl font-extrabold mb-4">Powerful <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-blue-400">Features</span></h1>
<p class="text-slate-400 max-w-2xl mx-auto">TaskHub comes packed with all the tools you need to manage your team, track progress, and keep your business running smoothly.</p>
</div>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
<div class="feature-card bg-white/5 rounded-2xl p-8 border border-white/10 animate-fade-up stagger-1">
<div class="w-14 h-14 rounded-xl bg-purple-600/20 flex items-center justify-center mb-5">
<svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
</div>
<h3 class="text-lg font-semibold mb-2">Task Management</h3>
<p class="text-sm text-slate-400 leading-relaxed">Create, assign, and track tasks with priority levels, due dates, and real-time status updates. Keep everyone accountable with clear ownership and deadlines.</p>
</div>
<div class="feature-card bg-white/5 rounded-2xl p-8 border border-white/10 animate-fade-up stagger-1">
<div class="w-14 h-14 rounded-xl bg-blue-600/20 flex items-center justify-center mb-5">
<svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/></svg>
</div>
<h3 class="text-lg font-semibold mb-2">Staff Management</h3>
<p class="text-sm text-slate-400 leading-relaxed">Manage your entire team from a single dashboard. Add staff members, assign them to departments, and control access levels with ease.</p>
</div>
<div class="feature-card bg-white/5 rounded-2xl p-8 border border-white/10 animate-fade-up stagger-2">
<div class="w-14 h-14 rounded-xl bg-amber-600/20 flex items-center justify-center mb-5">
<svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
</div>
<h3 class="text-lg font-semibold mb-2">Activity Tracking</h3>
<p class="text-sm text-slate-400 leading-relaxed">Monitor staff work logs, track task progress, and get real-time insights into team productivity. Know exactly what everyone is working on.</p>
</div>
<div class="feature-card bg-white/5 rounded-2xl p-8 border border-white/10 animate-fade-up stagger-2">
<div class="w-14 h-14 rounded-xl bg-emerald-600/20 flex items-center justify-center mb-5">
<svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
</div>
<h3 class="text-lg font-semibold mb-2">Department Management</h3>
<p class="text-sm text-slate-400 leading-relaxed">Organize your team into departments for better workflow management. Assign tasks at the department level and track performance per team.</p>
</div>
<div class="feature-card bg-white/5 rounded-2xl p-8 border border-white/10 animate-fade-up stagger-3">
<div class="w-14 h-14 rounded-xl bg-red-600/20 flex items-center justify-center mb-5">
<svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
</div>
<h3 class="text-lg font-semibold mb-2">Real-Time Updates</h3>
<p class="text-sm text-slate-400 leading-relaxed">Get instant updates when tasks are assigned, updated, or completed. Stay in the loop with live activity feeds and status changes.</p>
</div>
<div class="feature-card bg-white/5 rounded-2xl p-8 border border-white/10 animate-fade-up stagger-3">
<div class="w-14 h-14 rounded-xl bg-cyan-600/20 flex items-center justify-center mb-5">
<svg class="w-7 h-7 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
</div>
<h3 class="text-lg font-semibold mb-2">Secure &amp; Reliable</h3>
<p class="text-sm text-slate-400 leading-relaxed">Enterprise-grade security with encrypted passwords, session management, and data isolation. Your business data stays safe.</p>
</div>
<div class="feature-card bg-white/5 rounded-2xl p-8 border border-white/10 animate-fade-up stagger-3">
<div class="w-14 h-14 rounded-xl bg-pink-600/20 flex items-center justify-center mb-5">
<svg class="w-7 h-7 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
</div>
<h3 class="text-lg font-semibold mb-2">Customizable Dashboard</h3>
<p class="text-sm text-slate-400 leading-relaxed">Every business is unique. Customize your dashboard layout, theme, and preferences to match your workflow.</p>
</div>
<div class="feature-card bg-white/5 rounded-2xl p-8 border border-white/10 animate-fade-up stagger-3">
<div class="w-14 h-14 rounded-xl bg-indigo-600/20 flex items-center justify-center mb-5">
<svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
</div>
<h3 class="text-lg font-semibold mb-2">File Uploads</h3>
<p class="text-sm text-slate-400 leading-relaxed">Attach files and images to tasks. Share documents, screenshots, and resources with your team directly through the platform.</p>
</div>
<div class="feature-card bg-white/5 rounded-2xl p-8 border border-white/10 animate-fade-up stagger-3">
<div class="w-14 h-14 rounded-xl bg-teal-600/20 flex items-center justify-center mb-5">
<svg class="w-7 h-7 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
</div>
<h3 class="text-lg font-semibold mb-2">Data Privacy</h3>
<p class="text-sm text-slate-400 leading-relaxed">Your business data is isolated and secure. Each business operates in its own protected environment with full data encryption.</p>
</div>
</div>
</div>

<footer class="border-t border-white/5 bg-white/[.02] py-12">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
<div><div class="flex items-center gap-2 mb-4"><div class="w-6 h-6 rounded bg-purple-600 flex items-center justify-center text-xs font-bold">T</div><span class="font-bold"><span class="text-purple-400">Task</span>Hub</span></div><p class="text-sm text-slate-500 mb-2">Streamline your workflow, manage tasks, and grow your business.</p><p class="text-xs text-slate-500">Task Management &amp; Workflow Automation System</p></div>
<div><h4 class="font-semibold mb-4 text-sm uppercase tracking-wider text-slate-400">Product</h4><ul class="space-y-2 text-sm text-slate-500"><li><a href="features.php" class="hover:text-white transition">Features</a></li><li><a href="usermanual.php" class="hover:text-white transition">User Manual</a></li><li><a href="register.php" class="hover:text-white transition">Register</a></li></ul></div>
<div><h4 class="font-semibold mb-4 text-sm uppercase tracking-wider text-slate-400">Company</h4><ul class="space-y-2 text-sm text-slate-500"><li><a href="about.php" class="hover:text-white transition">About Us</a></li><li><a href="contact.php" class="hover:text-white transition">Contact</a></li></ul></div>
<div><h4 class="font-semibold mb-4 text-sm uppercase tracking-wider text-slate-400">Legal</h4><ul class="space-y-2 text-sm text-slate-500"><li><a href="privacy.php" class="hover:text-white transition">Privacy Policy</a></li><li><a href="terms.php" class="hover:text-white transition">Terms of Service</a></li></ul></div>
</div>
<div class="border-t border-white/5 pt-8 text-center text-xs text-slate-600">TaskHub &mdash; Developed by <span class="text-purple-400">Aakash Gaud</span> (<button onclick="var b=this;var l=this.nextElementSibling;b.classList.add('hidden');l.classList.remove('hidden');setTimeout(function(){l.classList.add('hidden');b.classList.remove('hidden')},5000)" class="text-purple-400 hover:text-purple-300 bg-purple-500/10 px-1.5 py-0.5 rounded text-xs font-medium inline-flex items-center gap-1">Show Email</button><a href="mailto:akashgaud7389@gmail.com" class="text-purple-400 hover:text-purple-300 hidden">akashgaud7389@gmail.com</a>) &copy; 2026 All Rights Reserved</div>
</div>
</footer>
</body>
</html>
