<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us — TaskHub</title>
<meta name="description" content="Get in touch with the TaskHub team. We're here to help with any questions, feedback, or support needs.">
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
.contact-card{transition:all .3s ease}
.contact-card:hover{transform:translateY(-4px);box-shadow:0 12px 40px rgba(147,51,234,.1)}
</style>
</head>
<body class="bg-slate-900 text-white antialiased">

<nav class="sticky top-0 z-50 bg-slate-900/80 backdrop-blur-xl border-b border-white/5">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
<a href="index.php" class="flex items-center gap-2"><div class="w-7 h-7 rounded bg-purple-600 flex items-center justify-center text-xs font-bold">T</div><span class="font-bold"><span class="text-purple-400">Task</span>Hub</span></a>
<div class="hidden md:flex items-center gap-6 text-sm">
<a href="index.php" class="text-slate-300 hover:text-white transition">Home</a>
<a href="features.php" class="text-slate-300 hover:text-white transition">Features</a>
<a href="usermanual.php" class="text-slate-300 hover:text-white transition">Manual</a>
<a href="about.php" class="text-slate-300 hover:text-white transition">About</a>
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
<span class="w-2 h-2 rounded-full bg-purple-400 animate-pulse"></span>Get in Touch
</div>
<h1 class="text-4xl sm:text-5xl font-extrabold mb-4">Contact <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-blue-400">Us</span></h1>
<p class="text-slate-400 max-w-2xl mx-auto">Have questions, feedback, or need support? We're here to help you get the most out of TaskHub.</p>
</div>

<div class="grid lg:grid-cols-2 gap-12 items-start">
<div class="space-y-8 animate-fade-up stagger-1">
<div class="contact-card bg-white/5 rounded-2xl p-6 border border-white/10 flex items-start gap-4">
<div class="w-12 h-12 rounded-xl bg-purple-600/20 flex items-center justify-center flex-shrink-0">
<svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
</div>
<div><h3 class="font-semibold text-lg">Email Us</h3><p class="text-sm text-slate-400 mt-1">Send us an email anytime and we'll get back within 24 hours.</p><a href="mailto:akashgaud7389@gmail.com" class="inline-block mt-2 text-purple-400 hover:text-purple-300 text-sm font-medium transition">akashgaud7389@gmail.com &rarr;</a></div>
</div>

<div class="contact-card bg-white/5 rounded-2xl p-6 border border-white/10 flex items-start gap-4">
<div class="w-12 h-12 rounded-xl bg-blue-600/20 flex items-center justify-center flex-shrink-0">
<svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
</div>
<div><h3 class="font-semibold text-lg">Location</h3><p class="text-sm text-slate-400 mt-1">Based in India. Serving businesses worldwide with our cloud platform.</p></div>
</div>

<div class="contact-card bg-white/5 rounded-2xl p-6 border border-white/10 flex items-start gap-4">
<div class="w-12 h-12 rounded-xl bg-emerald-600/20 flex items-center justify-center flex-shrink-0">
<svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
</div>
<div><h3 class="font-semibold text-lg">Quick Response</h3><p class="text-sm text-slate-400 mt-1">We prioritize your inquiries and aim to respond within 24 hours on business days.</p></div>
</div>

<div class="contact-card bg-white/5 rounded-2xl p-6 border border-white/10 flex items-start gap-4">
<div class="w-12 h-12 rounded-xl bg-amber-600/20 flex items-center justify-center flex-shrink-0">
<svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
</div>

</div>
</div>

<div class="animate-fade-up stagger-2">
<div class="bg-white/5 rounded-2xl p-8 border border-white/10">
<h2 class="text-xl font-bold mb-6">Send a Message</h2>
<form id="contactForm">
<div class="grid sm:grid-cols-2 gap-4 mb-4">
<div><label class="block text-xs text-slate-400 mb-1">Your Name</label><input type="text" id="contactName" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"></div>
<div><label class="block text-xs text-slate-400 mb-1">Your Email</label><input type="email" id="contactEmail" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"></div>
</div>
<div class="mb-4"><label class="block text-xs text-slate-400 mb-1">Subject</label><input type="text" id="contactSubject" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"></div>
<div class="mb-4"><label class="block text-xs text-slate-400 mb-1">Message</label><textarea id="contactMessage" rows="5" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea></div>
<button type="submit" class="w-full py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition">Send Message</button>
</form>
</div>
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

<script>
document.getElementById('contactForm')?.addEventListener('submit', function(e) {
e.preventDefault();
const btn = this.querySelector('button[type="submit"]');
btn.disabled = true;
btn.textContent = 'Sending...';
setTimeout(() => {
alert('Thank you for your message! We will get back to you soon.');
this.reset();
btn.disabled = false;
btn.textContent = 'Send Message';
}, 800);
});
</script>
</body>
</html>
