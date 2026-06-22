<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us — TaskHub</title>
<meta name="description" content="Learn about TaskHub — the task management platform built by Aakash Gaud for growing businesses.">
<link rel="icon" type="image/x-icon" href="favicon.ico">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{font-family:'Inter',sans-serif}
@keyframes fade-up{from{opacity:0;transform:translateY(40px)}to{opacity:1;transform:translateY(0)}}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-15px)}}
.animate-fade-up{animation:fade-up .8s ease-out forwards}
.animate-float{animation:float 6s ease-in-out infinite}
.stagger-1{animation-delay:.1s}.stagger-2{animation-delay:.2s}.stagger-3{animation-delay:.3s}
.stagger-4{animation-delay:.4s}
.about-card{transition:all .4s ease}
.about-card:hover{transform:translateY(-6px);box-shadow:0 15px 50px rgba(147,51,234,.12)}
</style>
</head>
<body class="bg-slate-900 text-white antialiased">

<nav class="sticky top-0 z-50 bg-slate-900/80 backdrop-blur-xl border-b border-white/5">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
<a href="index.php" class="flex items-center gap-2"><div class="w-7 h-7 rounded bg-purple-600 flex items-center justify-center text-xs font-bold">T</div><span class="font-bold"><span class="text-purple-400">Task</span>Hub</span></a>
<div class="hidden md:flex items-center gap-6 text-sm">
<a href="index.php" class="text-slate-300 hover:text-white transition">Home</a>
<a href="features.php" class="text-slate-300 hover:text-white transition">Features</a>
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
<div class="grid lg:grid-cols-2 gap-16 items-center mb-20">
<div class="animate-fade-up">
<div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-400 text-sm mb-4">
<span class="w-2 h-2 rounded-full bg-purple-400 animate-pulse"></span>About Us
</div>
<h1 class="text-4xl sm:text-5xl font-extrabold mb-6">Built for <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-blue-400">Growing Businesses</span></h1>
<p class="text-lg text-slate-400 leading-relaxed mb-6">
TaskHub was created by <strong class="text-white">Aakash Gaud</strong> to solve the real-world challenge of managing tasks and teams in small to medium businesses. We believe powerful project management shouldn't require complex enterprise tools.
</p>
<p class="text-slate-400 leading-relaxed">
Whether you're running a design agency, a software development firm, or a manufacturing unit, TaskHub adapts to your workflow. Assign tasks, track progress, and keep your team accountable — all from a single, intuitive dashboard.
</p>
</div>
<div class="relative animate-fade-up stagger-2">
<div class="rounded-2xl overflow-hidden border border-white/10 bg-gradient-to-br from-purple-900/20 to-slate-800/20 p-2">
<div class="rounded-xl overflow-hidden">
<img src="assets/images/team-working.svg" alt="Team Collaboration" class="w-full">
</div>
</div>
<div class="absolute -bottom-4 -right-4 w-36 h-24 rounded-2xl bg-gradient-to-br from-purple-600/20 to-blue-600/20 border border-purple-500/30 backdrop-blur-xl flex items-center justify-center animate-float">
<div class="text-center"><p class="text-lg font-bold text-purple-400">5+</p><p class="text-xs text-slate-400 px-2">Years Experience</p></div>
</div>
</div>
</div>

<div class="grid md:grid-cols-3 gap-8 mb-20">
<div class="about-card bg-white/5 rounded-2xl p-8 border border-white/10 text-center animate-fade-up stagger-1">
<div class="w-14 h-14 rounded-full bg-purple-600/20 flex items-center justify-center mx-auto mb-4">
<svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
</div>
<h3 class="text-lg font-semibold mb-2">Our Mission</h3>
<p class="text-sm text-slate-400 leading-relaxed">To provide an accessible, powerful, and intuitive platform that helps businesses streamline their operations without complexity or high costs.</p>
</div>
<div class="about-card bg-white/5 rounded-2xl p-8 border border-white/10 text-center animate-fade-up stagger-2">
<div class="w-14 h-14 rounded-full bg-blue-600/20 flex items-center justify-center mx-auto mb-4">
<svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
</div>
<h3 class="text-lg font-semibold mb-2">Our Vision</h3>
<p class="text-sm text-slate-400 leading-relaxed">A world where every small business has access to enterprise-grade management tools that are simple to use and affordable to maintain.</p>
</div>
<div class="about-card bg-white/5 rounded-2xl p-8 border border-white/10 text-center animate-fade-up stagger-3">
<div class="w-14 h-14 rounded-full bg-emerald-600/20 flex items-center justify-center mx-auto mb-4">
<svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
</div>
<h3 class="text-lg font-semibold mb-2">Our Values</h3>
<p class="text-sm text-slate-400 leading-relaxed">Simplicity, security, reliability, and continuous improvement. We build features that matter and maintain the highest standards of data protection.</p>
</div>
</div>

<div class="grid md:grid-cols-4 gap-6 p-10 rounded-2xl bg-gradient-to-r from-purple-900/20 to-blue-900/20 border border-white/5">
<div class="text-center animate-fade-up stagger-1"><p class="text-4xl font-bold text-purple-400">9+</p><p class="text-sm text-slate-400 mt-1">Features</p></div>
<div class="text-center animate-fade-up stagger-2"><p class="text-4xl font-bold text-blue-400">100+</p><p class="text-sm text-slate-400 mt-1">Active Users</p></div>
<div class="text-center animate-fade-up stagger-3"><p class="text-4xl font-bold text-emerald-400">1K+</p><p class="text-sm text-slate-400 mt-1">Tasks Managed</p></div>
<div class="text-center animate-fade-up stagger-4"><p class="text-4xl font-bold text-amber-400">99.9%</p><p class="text-sm text-slate-400 mt-1">Uptime</p></div>
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
