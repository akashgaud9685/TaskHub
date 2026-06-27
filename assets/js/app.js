'use strict';
/**
 * Shared utilities for TaskHub
 */

function showToast(message, type = 'success') {
    const colors = {
        success: 'bg-emerald-600',
        error: 'bg-red-600',
        info: 'bg-blue-600',
        warning: 'bg-amber-600',
    };

    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-[70] ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg transition-all duration-300 translate-x-full opacity-0`;
    toast.textContent = message;
    document.body.appendChild(toast);

    requestAnimationFrame(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
    });

    setTimeout(() => {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}

function confirmAction(message) {
    return new Promise((resolve) => {
        const overlay = document.createElement('div');
        overlay.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm';

        overlay.innerHTML = `
            <div class="bg-white dark:bg-slate-800 rounded-xl p-6 max-w-sm w-full mx-4 shadow-2xl border border-slate-200 dark:border-white/10">
                <p class="text-slate-800 dark:text-white text-lg mb-6">${message}</p>
                <div class="flex gap-3 justify-end">
                    <button class="cancelBtn px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-white transition">Cancel</button>
                    <button class="confirmBtn px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white transition">Confirm</button>
                </div>
            </div>
        `;

        document.body.appendChild(overlay);

        overlay.querySelector('.cancelBtn').onclick = () => { overlay.remove(); resolve(false); };
        overlay.querySelector('.confirmBtn').onclick = () => { overlay.remove(); resolve(true); };
        overlay.onclick = (e) => { if (e.target === overlay) { overlay.remove(); resolve(false); } };
    });
}

// ─── THEME TOGGLE ──────────────────────────────────
function initTheme() {
    const saved = localStorage.getItem('taskhub_theme') || 'dark';
    document.documentElement.setAttribute('data-theme', saved);
    updateThemeIcon(saved);
}

function toggleTheme() {
    const current = document.documentElement.getAttribute('data-theme');
    const next = current === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('taskhub_theme', next);
    updateThemeIcon(next);
}

function updateThemeIcon(theme) {
    document.querySelectorAll('.theme-toggle').forEach(el => {
        if (theme === 'dark') {
            el.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg><span class="hidden sm:inline">Dark</span>';
        } else {
            el.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg><span class="hidden sm:inline">Light</span>';
        }
    });
}

// ─── LOADER ─────────────────────────────────────────
function showLoader(msg) {
    if (document.getElementById('appLoader')) return;
    const d = document.createElement('div');
    d.id = 'appLoader';
    d.className = 'fixed inset-0 z-[60] flex items-center justify-center bg-black/40 backdrop-blur-sm';
    d.innerHTML = `<div class="flex flex-col items-center gap-3 px-8 py-6 rounded-2xl bg-white/10 backdrop-blur-xl border border-white/20 shadow-2xl">
        <div class="w-10 h-10 border-[3px] border-purple-500 border-t-transparent rounded-full animate-spin"></div>
        <p id="loaderMsg" class="text-white text-sm font-medium">${msg || 'Processing...'}</p>
    </div>`;
    document.body.appendChild(d);
}
function hideLoader() {
    const el = document.getElementById('appLoader');
    if (el) el.remove();
}

// Auto-init theme on load
document.addEventListener('DOMContentLoaded', initTheme);
