<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register Business — TaskHub</title>
<meta name="description" content="Register your business on TaskHub and start managing tasks, tracking work, and boosting team productivity.">
<link rel="icon" type="image/x-icon" href="favicon.ico">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
<link rel="stylesheet" href="assets/css/app.css">
<style>
*{font-family:'Inter',sans-serif}
@keyframes fade-up{from{opacity:0;transform:translateY(40px)}to{opacity:1;transform:translateY(0)}}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-12px)}}
.animate-fade-up{animation:fade-up .8s ease-out forwards}
.animate-float{animation:float 5s ease-in-out infinite}
.stagger-1{animation-delay:.1s}.stagger-2{animation-delay:.2s}.stagger-3{animation-delay:.3s}
.benefit-item{transition:all .3s ease}
.benefit-item:hover{transform:translateX(6px)}
</style>
</head>
<body class="min-h-screen bg-slate-900 text-white antialiased">

<div class="min-h-screen grid lg:grid-cols-2">
<!-- LEFT: Registration Form -->
<div class="flex items-center justify-center p-8 lg:p-16">
<div class="w-full max-w-lg animate-fade-up">
<div class="mb-8">
<a href="index.php" class="inline-flex items-center gap-2 mb-8">
<div class="w-7 h-7 rounded bg-purple-600 flex items-center justify-center text-xs font-bold">T</div>
<span class="font-bold"><span class="text-purple-400">Task</span>Hub</span>
</a>
<h1 class="text-3xl font-bold mb-2">Get Started Free</h1>
<p class="text-slate-400">Register your business and start managing tasks in minutes.</p>
</div>

<div class="bg-white/10 backdrop-blur-xl rounded-2xl p-8 shadow-2xl border border-white/10">
<h2 class="text-xl font-semibold mb-6">Business Registration</h2>
<form id="registerForm" autocomplete="off">
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
<div class="sm:col-span-2">
<label for="business_name" class="block text-sm font-medium text-slate-300 mb-1">Business Name <span class="text-red-400">*</span></label>
<input type="text" id="business_name" name="business_name" required
class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
</div>
<div class="sm:col-span-2">
<label for="owner_name" class="block text-sm font-medium text-slate-300 mb-1">Owner Name <span class="text-red-400">*</span></label>
<input type="text" id="owner_name" name="owner_name" required
class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
</div>
<div class="sm:col-span-2">
<label for="email" class="block text-sm font-medium text-slate-300 mb-1">Email Address <span class="text-red-400">*</span></label>
<input type="email" id="email" name="email" required
class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
<p class="text-xs text-slate-500 mt-1">Used for account-related communication</p>
</div>
<div>
<label for="phone" class="block text-sm font-medium text-slate-300 mb-1">Phone</label>
<input type="tel" id="phone" name="phone" pattern="[\d\s\-\+]+" inputmode="numeric"
onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode === 32 || event.charCode === 45 || event.charCode === 43"
class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
</div>
<div>
<label for="username" class="block text-sm font-medium text-slate-300 mb-1">Username <span class="text-red-400">*</span></label>
<input type="text" id="username" name="username" required
class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
</div>
<div class="sm:col-span-2">
<label for="password" class="block text-sm font-medium text-slate-300 mb-1">Password <span class="text-red-400">*</span></label>
<div class="relative">
<input type="password" id="password" name="password" required minlength="8"
class="w-full px-4 py-2.5 pr-10 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
<button type="button" onclick="togglePass(this)" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition p-1">
<svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
<svg class="w-5 h-5 eye-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1l22 22"/></svg>
</button>
</div>
</div>
<div class="sm:col-span-2">
<label for="address" class="block text-sm font-medium text-slate-300 mb-1">Business Address</label>
<textarea id="address" name="address" rows="2"
class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"></textarea>
</div>
</div>

<div id="registerError" class="mb-4 text-red-400 text-sm hidden"></div>

<div class="mb-4 px-4 py-3 bg-amber-500/10 border border-amber-500/30 rounded-lg text-xs text-amber-400 leading-relaxed">
<strong>Note:</strong> Once registered, your business details (name, owner, email, phone, address, username &amp; password) cannot be edited. To change any information, contact TaskHub admin.
</div>

<button type="submit" id="registerBtn"
class="w-full mt-4 py-2.5 px-4 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-slate-900 disabled:opacity-50 disabled:cursor-not-allowed">
<span id="registerBtnText">Register Business</span>
<span id="registerSpinner" class="hidden inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin ml-2"></span>
</button>
</form>
<p class="text-center text-slate-400 text-sm mt-4">
Already registered? <a href="login.php" class="text-purple-400 hover:text-purple-300 transition">Sign In &rarr;</a>
</p>
</div>

<div class="text-center mt-6">
<a href="index.php" class="text-sm text-slate-500 hover:text-slate-300 transition">&larr; Back to Home</a>
</div>
</div>
</div>

<!-- RIGHT: Benefits / Info -->
<div class="hidden lg:flex flex-col justify-center p-16 bg-gradient-to-br from-purple-900/30 via-slate-900 to-blue-900/30 border-l border-white/5">
<div class="max-w-md animate-fade-up stagger-1">
<div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-400 text-sm mb-6">
<span class="w-2 h-2 rounded-full bg-purple-400 animate-pulse"></span>
Start Free
</div>
<h2 class="text-3xl font-bold mb-4">Join <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-blue-400">50+</span> Businesses</h2>
<p class="text-slate-400 mb-8">Get started with TaskHub today. No credit card required. Free to start with a 14-day trial.</p>

<div class="space-y-5 mb-10">
<div class="benefit-item flex items-start gap-3">
<div class="w-8 h-8 rounded-lg bg-purple-600/20 flex items-center justify-center flex-shrink-0 mt-0.5"><svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
<div><p class="text-sm font-medium">Free to Start</p><p class="text-xs text-slate-500">No credit card required. Start managing tasks immediately.</p></div>
</div>
<div class="benefit-item flex items-start gap-3">
<div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center flex-shrink-0 mt-0.5"><svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
<div><p class="text-sm font-medium">Easy Setup</p><p class="text-xs text-slate-500">Register your business and invite your team in under 5 minutes.</p></div>
</div>
<div class="benefit-item flex items-start gap-3">
<div class="w-8 h-8 rounded-lg bg-emerald-600/20 flex items-center justify-center flex-shrink-0 mt-0.5"><svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
<div><p class="text-sm font-medium">Powerful Features</p><p class="text-xs text-slate-500">Task management, staff tracking, departments, and real-time updates.</p></div>
</div>
<div class="benefit-item flex items-start gap-3">
<div class="w-8 h-8 rounded-lg bg-amber-600/20 flex items-center justify-center flex-shrink-0 mt-0.5"><svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
<div><p class="text-sm font-medium">Secure &amp; Reliable</p><p class="text-xs text-slate-500">Enterprise-grade security with encrypted data and session management.</p></div>
</div>
<div class="benefit-item flex items-start gap-3">
<div class="w-8 h-8 rounded-lg bg-pink-600/20 flex items-center justify-center flex-shrink-0 mt-0.5"><svg class="w-4 h-4 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
<div><p class="text-sm font-medium">Dedicated Support</p><p class="text-xs text-slate-500">Quick response times and helpful support when you need it.</p></div>
</div>
</div>

<div class="p-6 rounded-xl bg-white/[.03] border border-white/5">
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white font-bold text-lg">AG</div>
<div><p class="text-sm font-medium">Built by Aakash Gaud</p><p class="text-xs text-slate-500">Developer &amp; Founder, TaskHub</p></div>
</div>
</div>
</div>
</div>
</div>

<script>
function togglePass(btn) {
const inp = btn.parentElement.querySelector('input');
const isPw = inp.type === 'password';
inp.type = isPw ? 'text' : 'password';
btn.querySelectorAll('.eye-icon').forEach(el => el.classList.toggle('hidden'));
}

document.getElementById('registerForm').addEventListener('submit', async function (e) {
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
if (data.success) {
window.location.href = data.redirect;
} else {
errEl.textContent = data.message;
errEl.classList.remove('hidden');
btn.disabled = false; text.textContent = 'Register Business'; spin.classList.add('hidden');
}
} catch (err) {
errEl.textContent = 'Network error. Please try again.';
errEl.classList.remove('hidden');
btn.disabled = false; text.textContent = 'Register Business'; spin.classList.add('hidden');
}
});
</script>

<footer class="border-t border-white/5 bg-white/[.02] py-12 mt-16">
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
