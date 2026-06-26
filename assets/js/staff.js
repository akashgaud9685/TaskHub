// ─── STATE ────────────────────────────────────────────
let currentTab = 'todo';
let tasks = [];

// ─── DOM REFS ────────────────────────────────────────
const taskList = document.getElementById('taskList');
const todoCount = document.getElementById('todoCount');
const progressCount = document.getElementById('progressCount');
const completedCount = document.getElementById('completedCount');

// ─── PHOTO UPLOAD ─────────────────────────────────────
let cropper = null;

const photoUpload = document.getElementById('photoUpload');
if (photoUpload) {
    photoUpload.addEventListener('change', function () {
        if (!this.files || !this.files[0]) return;
        const file = this.files[0];
        const reader = new FileReader();
        reader.onload = function (e) {
            const img = document.getElementById('cropImage');
            img.src = e.target.result;
            document.getElementById('cropModal').classList.remove('hidden');
            if (cropper) cropper.destroy();
            cropper = new Cropper(img, {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                cropBoxMovable: true,
                cropBoxResizable: true,
                background: false,
            });
        };
        reader.readAsDataURL(file);
        this.value = '';
    });
}

function uploadCroppedPhoto() {
    if (!cropper) return;
    const canvas = cropper.getCroppedCanvas({ width: 400, height: 400 });
    const btn = document.getElementById('cropUploadBtn');
    const btnText = document.getElementById('cropBtnText');
    const spinner = document.getElementById('cropSpinner');
    const progressWrap = document.getElementById('uploadProgressWrap');
    const progressBar = document.getElementById('uploadProgressBar');
    const progressText = document.getElementById('uploadProgressText');

    btn.disabled = true;
    btnText.textContent = 'Uploading...';
    spinner.classList.remove('hidden');
    progressWrap.classList.remove('hidden');

    canvas.toBlob(function (blob) {
        const formData = new FormData();
        formData.append('photo', blob, 'profile.jpg');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../api/upload.php', true);

        xhr.upload.onprogress = function (e) {
            if (e.lengthComputable) {
                const pct = Math.round((e.loaded / e.total) * 100);
                progressBar.style.width = pct + '%';
                progressText.textContent = pct + '%';
            }
        };

        xhr.onload = function () {
            try {
                const data = JSON.parse(xhr.responseText);
                if (data.success) { showToast('Photo updated!'); location.reload(); }
                else { showToast(data.message, 'error'); }
            } catch (err) { showToast('Upload failed', 'error'); }
            btn.disabled = false;
            btnText.textContent = 'Upload';
            spinner.classList.add('hidden');
        };

        xhr.onerror = function () {
            showToast('Upload failed', 'error');
            btn.disabled = false;
            btnText.textContent = 'Upload';
            spinner.classList.add('hidden');
        };

        xhr.send(formData);
    }, 'image/jpeg', 0.9);
}

function closeCropModal() {
    document.getElementById('cropModal').classList.add('hidden');
    document.getElementById('uploadProgressWrap').classList.add('hidden');
    document.getElementById('uploadProgressBar').style.width = '0%';
    document.getElementById('uploadProgressText').textContent = '0%';
    if (cropper) { cropper.destroy(); cropper = null; }
}

// ─── LOAD TASKS ──────────────────────────────────────
async function loadTasks(silent) {
    if (!silent) showLoader();
    try {
        const res = await fetch('../api/tasks.php');
        const data = await res.json();
        if (!data.success) throw new Error(data.message);
        tasks = data.data;
        renderTasks();
        updateCounts();
    } catch (err) {
        taskList.innerHTML = '<div class="text-center text-red-500 py-12">Failed to load tasks. Try refreshing.</div>';
    } finally {
        if (!silent) hideLoader();
    }
}

// ─── UPDATE COUNTS ───────────────────────────────────
function updateCounts() {
    const counts = { todo: 0, 'in-progress': 0, completed: 0 };
    tasks.forEach(t => { if (counts[t.status] !== undefined) counts[t.status]++; });
    todoCount.textContent = counts.todo;
    progressCount.textContent = counts['in-progress'];
    completedCount.textContent = counts.completed;
    const wc = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = v; };
    wc('welcomeTodoCount', counts.todo);
    wc('welcomeProgressCount', counts['in-progress']);
    wc('welcomeCompletedCount', counts.completed);
}

// ─── RENDER TASKS ────────────────────────────────────
function renderTasks() {
    const savedNotes = {};
    document.querySelectorAll('textarea[id^="notes-"]').forEach(el => {
        savedNotes[el.id] = el.value;
    });

    const filtered = tasks.filter(t => t.status === currentTab);

    if (filtered.length === 0) {
        const msgs = {
            'todo': 'No pending tasks assigned to you',
            'in-progress': 'No tasks in progress',
            'completed': 'No completed tasks yet',
        };
        taskList.innerHTML = `
            <div class="text-center py-16 rounded-xl border border-dashed" style="background:var(--bg-card); border-color:var(--empty-border);">
                <p class="text-lg" style="color:var(--text-secondary);">${msgs[currentTab] || 'No tasks'}</p>
                <p class="text-sm mt-1" style="color:var(--text-muted);">${currentTab === 'todo' ? 'New tasks assigned by admin will appear here' : 'Tasks will show here when you update their status'}</p>
            </div>
        `;
        return;
    }

    taskList.innerHTML = filtered.map(t => {
        const dueDate = new Date(t.due_date).toLocaleDateString('en-US', {
            month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit'
        });
        const isOverdue = new Date(t.due_date) < new Date() && t.status !== 'completed';

        const priorityStyles = {
            'low': 'bg-slate-500/20 text-slate-400',
            'medium': 'bg-blue-500/20 text-blue-400',
            'high': 'bg-orange-500/20 text-orange-400',
            'urgent': 'bg-red-500/20 text-red-400',
        };

        return `
            <div class="task-card fade-in" data-id="${t.id}">
                <div class="flex flex-col sm:flex-row items-start justify-between gap-4">
                    <div class="flex-1 min-w-0 w-full">
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <h3 class="font-semibold text-sm sm:text-base">${escHtml(t.title)}</h3>
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium ${priorityStyles[t.priority] || ''}">${escHtml(t.priority_label)}</span>
                            ${t.priority === 'urgent' ? '<span class="text-xs text-red-500 font-medium">HIGH PRIORITY</span>' : ''}
                        </div>
                        ${t.description ? `<p class="text-sm mt-1" style="color:var(--text-secondary);">${escHtml(t.description)}</p>` : ''}
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-3 text-xs" style="color:var(--text-muted);">
                            <span>Due: <span class="${isOverdue ? 'text-red-500 font-medium' : ''}">${dueDate}${isOverdue ? ' (Overdue)' : ''}</span></span>
                            <span>Dept: ${escHtml(t.department || 'N/A')}</span>
                        </div>
                        ${t.work_notes ? `<div class="mt-3 p-3 rounded-lg border" style="background:var(--notes-bg); border-color:var(--notes-border);"><p class="text-xs" style="color:var(--text-secondary);"><span class="font-medium">Notes:</span> ${escHtml(t.work_notes)}</p></div>` : ''}
                    </div>
                    <div class="flex-shrink-0 w-full sm:w-auto mt-2 sm:mt-0">
                        ${renderActions(t)}
                    </div>
                </div>
            </div>
        `;
    }).join('');

    Object.keys(savedNotes).forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = savedNotes[id];
    });
}

// ─── RENDER ACTION BUTTONS ──────────────────────────
function renderActions(task) {
    if (task.status === 'todo') {
        return `<button onclick="startTask(${task.id})" class="btn-primary text-sm w-full sm:w-auto whitespace-nowrap">Start Task</button>`;
    }

    if (task.status === 'in-progress') {
        return `
            <div class="flex flex-col gap-2 w-full sm:w-auto">
                <textarea id="notes-${task.id}" placeholder="Add work notes (optional)..." rows="2" 
                          class="text-sm w-full sm:w-64"></textarea>
                <div class="flex gap-2">
                    <button onclick="completeTask(${task.id}, '${escHtmlAttr(task.title)}')" class="btn-success text-sm flex-1 whitespace-nowrap">Mark Completed</button>
                    <button onclick="undoTask(${task.id}, 'todo')" class="btn-ghost text-sm px-3" title="Move back to To-Do">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                    </button>
                </div>
            </div>
        `;
    }

    if (task.status === 'completed') {
        return `
            <div class="flex gap-2">
                <span class="inline-flex items-center gap-1.5 text-emerald-500 text-sm font-medium"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Completed</span>
            </div>
        `;
    }

    return '';
}

// ─── START TASK ──────────────────────────────────────
async function startTask(taskId) {
    showLoader();
    try {
        const res = await fetch('../api/tasks.php', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${taskId}&status=in-progress`,
        });
        const data = await res.json();
        if (data.success) showToast('Task moved to In Progress!');
        else showToast(data.message, 'error');
    } catch (err) { showToast('Network error', 'error'); }
    finally { await loadTasks(true); hideLoader(); }
}

// ─── COMPLETE TASK ───────────────────────────────────
async function completeTask(taskId, title) {
    const confirmed = await confirmAction(`Mark "${title}" as completed?`);
    if (!confirmed) return;
    showLoader();
    const notes = document.getElementById(`notes-${taskId}`)?.value || '';
    try {
        const res = await fetch('../api/tasks.php', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${taskId}&status=completed&work_notes=${encodeURIComponent(notes)}`,
        });
        const data = await res.json();
        if (data.success) showToast('Task completed!');
        else showToast(data.message, 'error');
    } catch (err) { showToast('Network error', 'error'); }
    finally { await loadTasks(true); hideLoader(); }
}

// ─── UNDO TASK ───────────────────────────────────────
async function undoTask(taskId, targetStatus) {
    const label = targetStatus === 'todo' ? 'To-Do' : 'In Progress';
    const confirmed = await confirmAction(`Move this task back to "${label}"?`);
    if (!confirmed) return;
    showLoader();
    try {
        const res = await fetch('../api/tasks.php', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${taskId}&status=${targetStatus}`,
        });
        const data = await res.json();
        if (data.success) showToast(`Task moved back to ${label}`);
        else showToast(data.message, 'error');
    } catch (err) { showToast('Network error', 'error'); }
    finally { await loadTasks(true); hideLoader(); }
}

// ─── TAB SWITCHING ───────────────────────────────────
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('active', 'border-purple-500', 'text-purple-500');
            b.classList.add('border-transparent');
            b.style.color = 'var(--text-secondary)';
        });
        this.classList.add('active', 'border-purple-500', 'text-purple-500');
        this.classList.remove('border-transparent');
        this.style.color = '';
        currentTab = this.dataset.tab;
        renderTasks();
    });
});

// ─── LIVE CHECK ─────────────────────────────────────
let lastUpdate = 0;
let checkInterval;

function checkForUpdates() {
    fetch('../api/last_update.php')
        .then(r => r.text())
        .then(ts => {
            const t = parseInt(ts, 10);
            if (t > lastUpdate) { lastUpdate = t; loadTasks(true); }
        })
        .catch(() => {});
}

function startLiveCheck() {
    if (checkInterval) clearInterval(checkInterval);
    checkForUpdates();
    checkInterval = setInterval(checkForUpdates, 10000);
}

function stopLiveCheck() {
    if (checkInterval) { clearInterval(checkInterval); checkInterval = null; }
}

document.addEventListener('visibilitychange', () => {
    if (document.hidden) stopLiveCheck();
    else startLiveCheck();
});

// ─── SESSION CHECK ──────────────────────────────────
let sessionInterval;

function checkSession() {
    fetch('../api/check_session.php')
        .then(r => r.json())
        .then(d => {
            if (!d.valid) {
                setTimeout(() => { window.location.href = '../index.php?expired=1'; }, 1000);
            }
        })
        .catch(() => {});
}

function startSessionCheck() {
    if (sessionInterval) clearInterval(sessionInterval);
    sessionInterval = setInterval(checkSession, 30000);
}

function stopSessionCheck() {
    if (sessionInterval) { clearInterval(sessionInterval); sessionInterval = null; }
}

if (!document.hidden) { startLiveCheck(); startSessionCheck(); }
document.addEventListener('visibilitychange', () => {
    if (document.hidden) { stopLiveCheck(); stopSessionCheck(); }
    else { checkSession(); startLiveCheck(); startSessionCheck(); }
});

// ─── UTILITY ─────────────────────────────────────────
function escHtml(str) {
    const div = document.createElement('div');
    div.textContent = str || '';
    return div.innerHTML;
}

function escHtmlAttr(str) {
    return (str || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/\n/g, '\\n').replace(/\r/g, '').replace(/\//g, '\\/').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

// ─── PROFILE DROPDOWN ──────────────────────────────
function toggleProfileDropdown() {
    const menu = document.getElementById('profileDropdownMenu');
    const isHidden = menu.classList.contains('hidden');
    if (isHidden) {
        menu.classList.remove('hidden');
        menu.style.opacity = '0';
        menu.style.transform = 'translateY(-8px)';
        requestAnimationFrame(() => {
            menu.style.transition = 'all 0.2s';
            menu.style.opacity = '1';
            menu.style.transform = 'translateY(0)';
        });
    } else {
        menu.style.opacity = '0';
        menu.style.transform = 'translateY(-8px)';
        setTimeout(() => menu.classList.add('hidden'), 200);
    }
}

// ─── WORK LOG ─────────────────────────────────────────
function openWorkLogModal() {
    const el = document.getElementById('workLogDesc');
    if (el) el.value = '';
    const d = document.getElementById('workLogDate');
    if (d) d.value = new Date().toISOString().split('T')[0];
    document.getElementById('workLogModal').classList.remove('hidden');
}
function closeWorkLogModal() {
    document.getElementById('workLogModal').classList.add('hidden');
}

document.getElementById('workLogModal')?.addEventListener('click', function (e) {
    if (e.target === this) closeWorkLogModal();
});

document.getElementById('workLogForm')?.addEventListener('submit', async function (e) {
    e.preventDefault();
    const btn = document.getElementById('workLogSubmitBtn');
    const text = document.getElementById('workLogBtnText');
    const spin = document.getElementById('workLogSpinner');
    btn.disabled = true; text.textContent = 'Saving...'; spin.classList.remove('hidden');
    try {
        const formData = new FormData(this);
        const res = await fetch('../api/work_logs.php', { method: 'POST', body: formData });
        const data = await res.json();
        if (data.success) { showToast('Work logged successfully!'); closeWorkLogModal(); }
        else showToast(data.message, 'error');
    } catch (err) { showToast('Network error', 'error'); }
    finally { btn.disabled = false; text.textContent = 'Save'; spin.classList.add('hidden'); }
});

// ─── MY LOGS ───────────────────────────────────────────
function viewMyLogs() {
    document.getElementById('myLogsModal').classList.remove('hidden');
    loadMyLogs();
}

function closeMyLogsModal() {
    document.getElementById('myLogsModal').classList.add('hidden');
}

document.getElementById('myLogsModal')?.addEventListener('click', function (e) {
    if (e.target === this) closeMyLogsModal();
});

function applyMyLogsFilter() { loadMyLogs(); }

async function loadMyLogs() {
    const container = document.getElementById('myLogsContent');
    const params = new URLSearchParams();
    const dateFrom = document.getElementById('myLogsDateFrom').value;
    if (dateFrom) params.set('date_from', dateFrom);
    const dateTo = document.getElementById('myLogsDateTo').value;
    if (dateTo) params.set('date_to', dateTo);
    try {
        const res = await fetch(`../api/work_logs.php?${params}`);
        const data = await res.json();
        if (!data.success) throw new Error(data.message);
        if (data.data.length === 0) {
            container.innerHTML = '<div class="text-center text-[var(--text-muted)] py-8">No work logs found.</div>';
            return;
        }
        container.innerHTML = data.data.map(w => `
            <div class="border rounded-xl p-4" style="border-color:var(--border-color);background:var(--bg-card);">
                <div class="flex items-start justify-between gap-3">
                    <p class="text-sm whitespace-pre-wrap">${escHtml(w.description)}</p>
                    <span class="text-xs text-[var(--text-muted)] whitespace-nowrap">${w.log_date}</span>
                </div>
                <div class="flex items-center justify-between mt-2">
                    <p class="text-xs text-[var(--text-muted)]">Logged: ${new Date(w.created_at).toLocaleString()}</p>
                    <button onclick="openStaffReply(${w.id})" class="text-xs text-purple-500 hover:text-purple-400 transition font-medium">
                        ${parseInt(w.reply_count) > 0 ? `View (${w.reply_count})` : 'Reply'}
                    </button>
                </div>
            </div>
        `).join('');
    } catch (err) {
        container.innerHTML = '<div class="text-center text-red-500 py-8">Failed to load logs</div>';
    }
}

// ─── WORK LOG REPLY (STAFF) ─────────────────────────
let staffReplyAutoRefresh = null;
let staffLastReplyCount = -1;
let staffReplyLastTs = 0;

async function openStaffReply(workLogId) {
    document.getElementById('staffReplyWorkLogId').value = workLogId;
    document.getElementById('staffReplyMessage').value = '';
    document.getElementById('staffReplyModal').classList.remove('hidden');
    document.getElementById('staffReplyThread').innerHTML = '<p class="text-xs text-[var(--text-muted)] text-center py-4">Loading...</p>';
    staffLastReplyCount = -1;

    try {
        const [logRes, repliesRes] = await Promise.all([
            fetch(`../api/work_logs.php?id=${workLogId}`),
            fetch(`../api/work_log_replies.php?work_log_id=${workLogId}`)
        ]);

        const logData = await logRes.json();
        const log = logData.data && logData.data.length > 0 ? logData.data[0] : logData.data;
        if (log) {
            document.getElementById('staffReplyOriginal').innerHTML = `
                <div>
                    <p class="text-sm whitespace-pre-wrap">${escHtml(log.description || log.message || '')}</p>
                    <p class="text-xs text-[var(--text-muted)] mt-2">${log.log_date || ''} &middot; ${new Date(log.created_at).toLocaleString()}</p>
                </div>
            `;
        }

        const repliesData = await repliesRes.json();
        renderStaffReplies(repliesData, true);
    } catch (_) {
        document.getElementById('staffReplyThread').innerHTML = '<p class="text-xs text-red-500 text-center py-4">Failed to load</p>';
    }

    try {
        const tr = await fetch('../api/last_update.php');
        staffReplyLastTs = parseInt(await tr.text(), 10) || 0;
    } catch (_) { staffReplyLastTs = 0; }

    startStaffReplyAutoRefresh(workLogId);
}

function closeStaffReplyModal() {
    document.getElementById('staffReplyModal').classList.add('hidden');
    stopStaffReplyAutoRefresh();
    staffLastReplyCount = -1;
}

function scrollStaffReplyToBottom() {
    const container = document.getElementById('staffReplyModal').querySelector('.max-h-\\[90vh\\]');
    if (container) {
        requestAnimationFrame(() => { container.scrollTop = container.scrollHeight; });
    }
}

function renderStaffReplies(data, force) {
    if (!data.success) return;
    if (!force && data.data.length === staffLastReplyCount) return;
    staffLastReplyCount = data.data.length;
    const container = document.getElementById('staffReplyThread');
    if (data.data.length === 0) {
        container.innerHTML = '<p class="text-xs text-[var(--text-muted)] text-center py-4">No replies yet</p>';
        return;
    }
    const wasNearBottom = container && (container.scrollHeight - container.scrollTop - container.clientHeight) < 100;
    container.innerHTML = data.data.map(r => `
        <div class="flex ${r.user_role === 'staff' ? 'justify-end' : 'justify-start'}">
            <div class="max-w-[85%] p-3 rounded-2xl border" style="background:${r.user_role === 'staff' ? 'rgba(59,130,246,0.15)' : 'var(--bg-card)'}; border-color:${r.user_role === 'staff' ? 'rgba(59,130,246,0.3)' : 'var(--border-color)'};">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-medium">${escHtml(r.user_name)}</span>
                    <span class="text-xs px-1.5 py-0.5 rounded-full ${r.user_role === 'admin' ? 'bg-purple-500/20 text-purple-400' : 'bg-blue-500/20 text-blue-400'}">${r.user_role}</span>
                </div>
                <p class="text-sm whitespace-pre-wrap">${escHtml(r.message)}</p>
                <p class="text-xs text-[var(--text-muted)] mt-1">${new Date(r.created_at).toLocaleString()}</p>
            </div>
        </div>
    `).join('');
    if (force || wasNearBottom) scrollStaffReplyToBottom();
}

async function loadStaffReplies(workLogId) {
    try {
        const res = await fetch(`../api/work_log_replies.php?work_log_id=${workLogId}`);
        const data = await res.json();
        renderStaffReplies(data, false);
    } catch (_) {}
}

function startStaffReplyAutoRefresh(workLogId) {
    stopStaffReplyAutoRefresh();
    staffReplyAutoRefresh = setInterval(async () => {
        try {
            const res = await fetch('../api/last_update.php');
            const ts = parseInt(await res.text(), 10);
            if (ts > staffReplyLastTs) {
                staffReplyLastTs = ts;
                await loadStaffReplies(workLogId);
            }
        } catch (_) {}
    }, 2000);
}

function stopStaffReplyAutoRefresh() {
    if (staffReplyAutoRefresh) {
        clearInterval(staffReplyAutoRefresh);
        staffReplyAutoRefresh = null;
    }
}

document.getElementById('staffReplyForm')?.addEventListener('submit', async function (e) {
    e.preventDefault();
    const workLogId = document.getElementById('staffReplyWorkLogId').value;
    const message = document.getElementById('staffReplyMessage').value.trim();
    if (!message) return;
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    try {
        const formData = new FormData();
        formData.append('work_log_id', workLogId);
        formData.append('message', message);
        const res = await fetch('../api/work_log_replies.php', { method: 'POST', body: formData });
        const data = await res.json();
        if (data.success) {
            document.getElementById('staffReplyMessage').value = '';
            staffLastReplyCount = -1;
            staffReplyLastTs = Date.now() / 1000;
            await loadStaffReplies(workLogId);
        } else {
            showToast(data.message, 'error');
        }
    } catch (err) {
        showToast('Network error', 'error');
    } finally {
        btn.disabled = false;
    }
});

document.getElementById('staffReplyMessage')?.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        document.getElementById('staffReplyForm')?.requestSubmit();
    }
});

document.getElementById('staffReplyModal')?.addEventListener('click', function (e) {
    if (e.target === this) closeStaffReplyModal();
});

// Close dropdown on outside click
document.addEventListener('click', function (e) {
    const wrapper = document.getElementById('profileDropdownWrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        const menu = document.getElementById('profileDropdownMenu');
        if (menu && !menu.classList.contains('hidden')) {
            menu.classList.add('hidden');
            menu.style.opacity = '';
            menu.style.transform = '';
        }
    }
});

// Close crop modal on backdrop click
document.getElementById('cropModal')?.addEventListener('click', function (e) {
    if (e.target === this) closeCropModal();
});

// ─── INIT ────────────────────────────────────────────
loadTasks();
fetch('../api/last_update.php').then(r => r.text()).then(ts => {
    lastUpdate = parseInt(ts, 10) || 0;
});
