<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In — TaskHub</title>
<meta name="description" content="Sign in to your TaskHub account. Admin and staff login available.">
<link rel="icon" type="image/x-icon" href="favicon.ico">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{font-family:'Inter',sans-serif}
@keyframes fade-up{from{opacity:0;transform:translateY(40px)}to{opacity:1;transform:translateY(0)}}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-12px)}}
.animate-fade-up{animation:fade-up .8s ease-out forwards}
.animate-float{animation:float 5s ease-in-out infinite}
.stagger-1{animation-delay:.1s}.stagger-2{animation-delay:.2s}.stagger-3{animation-delay:.3s}
.benefit-card{transition:all .3s ease}
.benefit-card:hover{transform:translateX(6px)}
</style>
</head>
<body class="min-h-screen bg-slate-900 text-white antialiased">

<div class="min-h-screen grid lg:grid-cols-2">
<!-- LEFT: Login Form -->
<div class="flex items-center justify-center p-8 lg:p-16">
<div class="w-full max-w-md animate-fade-up">
<div class="mb-8">
<a href="index.php" class="inline-flex items-center gap-2 mb-8">
<div class="w-7 h-7 rounded bg-purple-600 flex items-center justify-center text-xs font-bold">T</div>
<span class="font-bold"><span class="text-purple-400">Task</span>Hub</span>
</a>
<h1 class="text-3xl font-bold mb-2">Welcome Back</h1>
<p class="text-slate-400">Sign in to manage your tasks and team.</p>
</div>

<div class="bg-white/5 rounded-2xl p-8 border border-white/10">
<div class="flex bg-white/5 rounded-lg p-1 mb-6">
<button type="button" onclick="setRole('admin')" id="roleAdmin" class="flex-1 py-2 text-sm font-medium rounded-md transition bg-purple-600 text-white">Admin</button>
<button type="button" onclick="setRole('staff')" id="roleStaff" class="flex-1 py-2 text-sm font-medium rounded-md transition text-slate-400 hover:text-white">Staff</button>
</div>
<h3 class="text-base font-semibold mb-4" id="loginHeading">Admin Login</h3>
<form id="loginForm" autocomplete="off" action="api/auth.php" method="POST">
<div id="businessCodeGroup" class="mb-4 hidden">
<label for="business_code" class="block text-sm font-medium text-slate-300 mb-1">Business Code</label>
<input type="text" id="business_code" name="business_code" placeholder="e.g. GAYA-71799" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500">
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
<div class="mt-4 text-center"><a href="register.php" class="text-sm text-purple-400 hover:text-purple-300 transition">Don't have an account? Register &rarr;</a></div>
</form>
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
Why TaskHub?
</div>
<h2 class="text-3xl font-bold mb-8">Everything you need to <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-blue-400">stay productive</span></h2>

<div class="space-y-6">
<div class="benefit-card flex items-start gap-4 p-4 rounded-xl bg-white/[.03] border border-white/5">
<div class="w-10 h-10 rounded-lg bg-purple-600/20 flex items-center justify-center flex-shrink-0">
<svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
</div>
<div><h4 class="font-semibold">Task Management</h4><p class="text-sm text-slate-400 mt-1">Create, assign, and track tasks with priorities and deadlines.</p></div>
</div>

<div class="benefit-card flex items-start gap-4 p-4 rounded-xl bg-white/[.03] border border-white/5">
<div class="w-10 h-10 rounded-lg bg-blue-600/20 flex items-center justify-center flex-shrink-0">
<svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/></svg>
</div>
<div><h4 class="font-semibold">Staff Management</h4><p class="text-sm text-slate-400 mt-1">Manage your team, departments, and access levels.</p></div>
</div>

<div class="benefit-card flex items-start gap-4 p-4 rounded-xl bg-white/[.03] border border-white/5">
<div class="w-10 h-10 rounded-lg bg-emerald-600/20 flex items-center justify-center flex-shrink-0">
<svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
</div>
<div><h4 class="font-semibold">Real-Time Tracking</h4><p class="text-sm text-slate-400 mt-1">Monitor work logs and activity updates as they happen.</p></div>
</div>

<div class="benefit-card flex items-start gap-4 p-4 rounded-xl bg-white/[.03] border border-white/5">
<div class="w-10 h-10 rounded-lg bg-amber-600/20 flex items-center justify-center flex-shrink-0">
<svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
</div>
<div><h4 class="font-semibold">Secure &amp; Private</h4><p class="text-sm text-slate-400 mt-1">Your business data is encrypted, isolated, and protected.</p></div>
</div>
</div>

<div class="mt-10 p-6 rounded-xl bg-white/[.03] border border-white/5">
<div class="flex items-center gap-3 mb-3">
<img src="assets/images/team-working.svg" alt="Team" class="w-10 h-10 rounded-lg">
<div><p class="text-sm font-medium">Trusted by growing businesses</p><p class="text-xs text-slate-500">Join 50+ businesses using TaskHub</p></div>
</div>
</div>
</div>
</div>
</div>

<script>
let loginRole = 'admin';
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('role') === 'staff') { loginRole = 'staff'; setRole('staff'); }
if (urlParams.get('expired') === '1') {
setTimeout(() => {
const el = document.getElementById('loginError');
el.textContent = 'Session expired. Please login again.';
el.classList.remove('hidden');
}, 300);
}
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
</script>
</body>
</html>
