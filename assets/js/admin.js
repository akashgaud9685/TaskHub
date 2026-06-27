// ─── SECTION NAVIGATION ────────────────────────────
function switchSection(section) {
    document.querySelectorAll('[id^="section"]').forEach(el => el.classList.add('hidden'));
    document.getElementById('section' + section.charAt(0).toUpperCase() + section.slice(1)).classList.remove('hidden');
    document.querySelectorAll('.nav-btn').forEach(b => {
        b.style.background = '';
        b.style.color = 'var(--text-secondary)';
    });
    const btn = document.getElementById('nav' + section.charAt(0).toUpperCase() + section.slice(1));
    btn.style.background = 'var(--bg-card)';
    btn.style.color = 'var(--text-primary)';
    if (section === 'staff') loadStaff();
    if (section === 'departments') loadDepts();
    if (section === 'activity') { loadActivity(); document.getElementById('activityBadge').classList.add('hidden'); }
    sessionStorage.setItem('admin_section', section);
}

// ─── STAFF MANAGEMENT ────────────────────────────────

const staffForm = document.getElementById('staffForm');
const staffTableBody = document.getElementById('staffTableBody');

async function loadStaff(silent) {
    if (!silent) showLoader();
    try {
        const res = await fetch('../api/staff.php');
        const data = await res.json();
        if (!data.success) throw new Error(data.message);

        if (data.data.length === 0) {
            staffTableBody.innerHTML = '<tr><td colspan="6" class="text-center text-[var(--text-muted)] py-8">No staff members yet. Click "+ New Staff" to add one.</td></tr>';
            if (!silent) hideLoader();
            return;
        }

        staffTableBody.innerHTML = data.data.map(s => `
            <tr>
                <td>
                    ${s.photo
                        ? `<img src="../uploads/profile/${escHtml(s.photo)}" alt="" class="w-8 h-8 rounded-full object-cover">`
                        : `<span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-purple-500/20 text-purple-400 text-xs font-bold">${escHtml(s.name.charAt(0).toUpperCase())}</span>`
                    }
                </td>
                <td class="font-medium">${escHtml(s.name)}</td>
                <td class="text-[var(--text-secondary)]">${escHtml(s.username)}</td>
                <td class="text-[var(--text-secondary)]">${escHtml(s.department || '—')}</td>
                <td>
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium ${s.status === 'active' ? 'text-emerald-500' : 'text-red-500'}">
                        <span class="w-1.5 h-1.5 rounded-full ${s.status === 'active' ? 'bg-emerald-500' : 'bg-red-500'}"></span>
                        ${s.status}
                    </span>
                </td>
                <td>
                    <div class="flex gap-2 flex-wrap">
                        <button onclick="editStaff(${s.id}, '${escHtmlAttr(s.name)}', '${escHtmlAttr(s.username)}', '${escHtmlAttr(s.department || '')}', '${s.status}')"
                                class="text-xs text-blue-500 hover:text-blue-400 transition font-medium">Edit</button>
                        <button onclick="deleteStaffPhoto(${s.id}, '${escHtmlAttr(s.photo || '')}')"
                                class="text-xs font-medium transition ${s.photo ? 'text-red-500 hover:text-red-400' : 'text-[var(--text-muted)] cursor-not-allowed'}" ${s.photo ? '' : 'disabled'}>Delete Photo</button>
                        <button onclick="toggleStaff(${s.id})"
                                class="text-xs font-medium transition ${s.status === 'active' ? 'text-amber-500 hover:text-amber-400' : 'text-emerald-500 hover:text-emerald-400'}">
                            ${s.status === 'active' ? 'Deactivate' : 'Activate'}
                        </button>
                        <button onclick="deleteStaff(${s.id}, '${escHtmlAttr(s.name)}')"
                                class="text-xs text-red-500 hover:text-red-400 transition font-medium">Delete</button>
                    </div>
                </td>
            </tr>
        `).join('');
    } catch (err) {
        staffTableBody.innerHTML = '<tr><td colspan="6" class="text-center text-red-500 py-8">Failed to load staff</td></tr>';
    } finally {
        if (!silent) hideLoader();
    }
}

async function deleteStaffPhoto(id, photo) {
    if (!photo) return;
    const confirmed = await confirmAction('Delete this staff member\'s profile photo?');
    if (!confirmed) return;
    showLoader();
    try {
        const res = await fetch('../api/staff.php', {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id}&delete_photo=1`,
        });
        const data = await res.json();
        if (data.success) { showToast(data.message); location.reload(); }
        else { hideLoader(); showToast(data.message, 'error'); }
    } catch (err) { hideLoader(); showToast('Network error', 'error'); }
}

function resetStaffForm() {
    document.getElementById('staffId').value = '';
    document.getElementById('staffName').value = '';
    document.getElementById('staffUsername').value = '';
    document.getElementById('staffPassword').value = '';
    document.getElementById('staffPassword').required = true;
    document.getElementById('staffPassword').placeholder = 'Min 6 chars for new';
    document.getElementById('staffDepartment').value = '';
    document.getElementById('staffSubmitBtn').textContent = 'Create';
    staffForm.classList.add('hidden');
}

function editStaff(id, name, username, department, status) {
    document.getElementById('staffId').value = id;
    document.getElementById('staffName').value = name;
    document.getElementById('staffUsername').value = username;
    document.getElementById('staffPassword').value = '';
    document.getElementById('staffPassword').required = false;
    document.getElementById('staffPassword').placeholder = 'Leave blank to keep';
    document.getElementById('staffDepartment').value = department;
    document.getElementById('staffSubmitBtn').textContent = 'Update';
    staffForm.classList.remove('hidden');
    staffForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

async function toggleStaff(id) {
    const confirmed = await confirmAction('Toggle this staff member\'s status?');
    if (!confirmed) return;
    showLoader();
    try {
        const res = await fetch('../api/staff.php', {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id}`,
        });
        const data = await res.json();
        if (data.success) { showToast(data.message); location.reload(); }
        else { hideLoader(); showToast(data.message, 'error'); }
    } catch (err) { hideLoader(); showToast('Network error', 'error'); }
}

async function deleteStaff(id, name) {
    const confirmed = await confirmAction(`Delete staff "${name}" and all their tasks? This cannot be undone.`);
    if (!confirmed) return;
    showLoader();
    try {
        const res = await fetch('../api/staff.php', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id}`,
        });
        const data = await res.json();
        if (data.success) { showToast(data.message); location.reload(); }
        else { hideLoader(); showToast(data.message, 'error'); }
    } catch (err) { hideLoader(); showToast('Network error', 'error'); }
}

staffForm.addEventListener('submit', async function (e) {
    e.preventDefault();
    showLoader();
    const id = document.getElementById('staffId').value;
    const name = document.getElementById('staffName').value;
    const username = document.getElementById('staffUsername').value;
    const password = document.getElementById('staffPassword').value;
    const department = document.getElementById('staffDepartment').value;

    if (id) {
        const body = `id=${id}&name=${encodeURIComponent(name)}&department=${encodeURIComponent(department)}&status=active${password ? '&password=' + encodeURIComponent(password) : ''}`;
        try {
            const res = await fetch('../api/staff.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body,
            });
            const data = await res.json();
            if (data.success) { showToast(data.message); location.reload(); }
            else { hideLoader(); showToast(data.message, 'error'); }
        } catch (err) { hideLoader(); showToast('Network error', 'error'); }
    } else {
        const formData = new FormData(this);
        try {
            const res = await fetch('../api/staff.php', {
                method: 'POST',
                body: formData,
            });
            const data = await res.json();
            if (data.success) { showToast(data.message); location.reload(); }
            else { hideLoader(); showToast(data.message, 'error'); }
        } catch (err) { hideLoader(); showToast('Network error', 'error'); }
    }
});


// ─── DEPARTMENT MANAGEMENT ──────────────────────────

const deptForm = document.getElementById('deptForm');
const deptTableBody = document.getElementById('deptTableBody');

async function loadDepts(silent) {
    if (!silent) showLoader();
    try {
        const res = await fetch('../api/departments.php');
        const data = await res.json();
        if (!data.success) throw new Error(data.message);

        if (data.data.length === 0) {
            deptTableBody.innerHTML = '<tr><td colspan="3" class="text-center text-[var(--text-muted)] py-8">No departments yet.</td></tr>';
            if (!silent) hideLoader();
            return;
        }

        deptTableBody.innerHTML = data.data.map(d => {
            const isMgmt = d.name.toLowerCase() === 'management';
            return `
            <tr>
                <td class="font-medium">
                    ${escHtml(d.name)}
                    ${isMgmt ? '<span class="ml-2 text-xs px-1.5 py-0.5 rounded-full bg-purple-500/20 text-purple-400 font-medium border border-purple-500/30">Reserved</span>' : ''}
                </td>
                <td class="text-[var(--text-muted)] text-xs">${new Date(d.created_at).toLocaleDateString()}</td>
                <td>
                    <div class="flex gap-2">
                        ${isMgmt
                            ? '<span class="text-xs text-[var(--text-muted)]">—</span>'
                            : `<button onclick="editDept(${d.id}, '${escHtmlAttr(d.name)}')" class="text-xs text-blue-500 hover:text-blue-400 transition font-medium">Edit</button>
                               <button onclick="deleteDept(${d.id}, '${escHtmlAttr(d.name)}')" class="text-xs text-red-500 hover:text-red-400 transition font-medium">Delete</button>`
                        }
                    </div>
                </td>
            </tr>`;
        }).join('');
    } catch (err) {
        deptTableBody.innerHTML = '<tr><td colspan="3" class="text-center text-red-500 py-8">Failed to load</td></tr>';
    } finally {
        if (!silent) hideLoader();
    }
}

function editDept(id, name) {
    const newName = prompt('Edit department name:', name);
    if (!newName || newName === name) return;
    showLoader();
    fetch('../api/departments.php', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}&name=${encodeURIComponent(newName)}`,
    }).then(r => r.json()).then(d => {
        if (d.success) { showToast(d.message); location.reload(); }
        else { hideLoader(); showToast(d.message, 'error'); }
    }).catch(() => { hideLoader(); showToast('Network error', 'error'); });
}

async function deleteDept(id, name) {
    const confirmed = await confirmAction(`Delete department "${name}"?`);
    if (!confirmed) return;
    showLoader();
    try {
        const res = await fetch('../api/departments.php', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id}`,
        });
        const data = await res.json();
        if (data.success) { showToast(data.message); location.reload(); }
        else { hideLoader(); showToast(data.message, 'error'); }
    } catch (err) { hideLoader(); showToast('Network error', 'error'); }
}

deptForm.addEventListener('submit', async function (e) {
    e.preventDefault();
    const name = document.getElementById('deptName').value.trim();
    if (!name) return;
    showLoader();
    try {
        const formData = new FormData(this);
        const res = await fetch('../api/departments.php', { method: 'POST', body: formData });
        const data = await res.json();
        if (data.success) { showToast(data.message); location.reload(); }
        else { hideLoader(); showToast(data.message, 'error'); }
    } catch (err) { hideLoader(); showToast('Network error', 'error'); }
});


// ─── TASK MANAGEMENT ─────────────────────────────────

const taskForm = document.getElementById('taskForm');
const taskTableBody = document.getElementById('taskTableBody');
let currentStatusFilter = 'all';

async function loadTasks(silent) {
    if (!silent) showLoader();
    const params = new URLSearchParams();
    if (currentStatusFilter !== 'all') params.set('status', currentStatusFilter);
    const staffFilter = document.getElementById('filterStaff').value;
    if (staffFilter) params.set('assigned_to', staffFilter);
    const priorityFilter = document.getElementById('filterPriority').value;
    if (priorityFilter) params.set('priority', priorityFilter);
    const dateFrom = document.getElementById('filterDateFrom').value;
    if (dateFrom) params.set('date_from', dateFrom);
    const dateTo = document.getElementById('filterDateTo').value;
    if (dateTo) params.set('date_to', dateTo);

    try {
        const res = await fetch(`../api/tasks.php?${params}`);
        const data = await res.json();
        if (!data.success) throw new Error(data.message);

        if (data.data.length === 0) {
            taskTableBody.innerHTML = '<tr><td colspan="8" class="text-center text-[var(--text-muted)] py-8">No tasks found matching your filters</td></tr>';
            return;
        }

        taskTableBody.innerHTML = data.data.map(t => {
            const dueDate = new Date(t.due_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' });
            const createdDate = new Date(t.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            const isOverdue = new Date(t.due_date) < new Date() && t.status !== 'completed';

            const statusStyles = {
                'todo': 'bg-amber-500/20 text-amber-500 border border-amber-500/30',
                'in-progress': 'bg-blue-500/20 text-blue-500 border border-blue-500/30',
                'completed': 'bg-emerald-500/20 text-emerald-500 border border-emerald-500/30',
            };

            const priorityStyles = {
                'low': 'bg-slate-500/20 text-slate-400',
                'medium': 'bg-blue-500/20 text-blue-400',
                'high': 'bg-orange-500/20 text-orange-400',
                'urgent': 'bg-red-500/20 text-red-400',
            };

            return `
                <tr>
                    <td class="font-medium">${escHtml(t.title)}</td>
                    <td class="text-[var(--text-secondary)]">${escHtml(t.assigned_name)}</td>
                    <td><span class="inline-block text-xs px-2 py-0.5 rounded-full font-medium ${priorityStyles[t.priority] || ''}">${escHtml(t.priority_label)}</span></td>
                    <td><span class="inline-block text-xs px-2 py-0.5 rounded-full font-medium ${statusStyles[t.status] || ''}">${t.status_label}</span></td>
                    <td class="${isOverdue ? 'text-red-500' : 'text-[var(--text-secondary)]'}">${dueDate}${isOverdue ? ' <span class="text-xs">(Overdue)</span>' : ''}</td>
                    <td class="text-[var(--text-secondary)] max-w-[150px]">
                        ${t.work_notes
                            ? (t.work_notes.length > 50
                                ? `<span class="truncate block">${escHtml(t.work_notes.slice(0, 50))}...</span><button onclick="showNoteModal(this)" data-note="${escHtml(t.work_notes)}" class="text-xs text-purple-500 hover:text-purple-400 transition font-medium mt-1">View Note</button>`
                                : escHtml(t.work_notes))
                            : '—'
                        }
                    </td>
                    <td class="text-[var(--text-muted)] text-xs">${createdDate}</td>
                    <td>
                        <div class="flex gap-2 items-center">
                            <button onclick="openTaskModal(${t.id})" class="text-xs text-blue-500 hover:text-blue-400 transition font-medium">Edit</button>
                            <button onclick="deleteTask(${t.id}, '${escHtmlAttr(t.title)}')" class="text-xs text-red-500 hover:text-red-400 transition font-medium">Delete</button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    } catch (err) {
        taskTableBody.innerHTML = '<tr><td colspan="8" class="text-center text-red-500 py-8">Failed to load tasks</td></tr>';
    } finally {
        if (!silent) hideLoader();
    }
}

function applyFilters() { loadTasks(); }

taskForm.addEventListener('submit', async function (e) {
    e.preventDefault();
    showLoader();
    const dateVal = document.getElementById('taskDueDate').value;
    const timeVal = document.getElementById('taskDueTime').value;
    const formData = new FormData(this);
    formData.set('due_date', timeVal ? `${dateVal} ${timeVal}:00` : `${dateVal} 23:59:59`);
    try {
        const res = await fetch('../api/tasks.php', { method: 'POST', body: formData });
        const data = await res.json();
        if (data.success) { showToast(data.message); location.reload(); }
        else { hideLoader(); showToast(data.message, 'error'); }
    } catch (err) { hideLoader(); showToast('Network error', 'error'); }
});

// ─── DELETE TASK ─────────────────────────────────────
async function deleteTask(taskId, title) {
    const confirmed = await confirmAction(`Delete task "${title}"? This cannot be undone.`);
    if (!confirmed) return;
    showLoader();
    try {
        const res = await fetch('../api/tasks.php', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${taskId}`,
        });
        const data = await res.json();
        if (data.success) { showToast('Task deleted'); location.reload(); }
        else { hideLoader(); showToast(data.message, 'error'); }
    } catch (err) { hideLoader(); showToast('Network error', 'error'); }
}

// ─── FILTER BUTTONS ──────────────────────────────────
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.filter-btn').forEach(b => {
            b.classList.remove('bg-purple-600', 'text-white');
            b.classList.add('bg-slate-700', 'text-slate-300');
        });
        this.classList.remove('bg-slate-700', 'text-slate-300');
        this.classList.add('bg-purple-600', 'text-white');
        currentStatusFilter = this.dataset.filter;
        loadTasks();
    });
});

// ─── EDIT TASK MODAL ────────────────────────────────
async function openTaskModal(taskId) {
    showLoader();
    try {
        const res = await fetch(`../api/tasks.php?id=${taskId}`);
        const data = await res.json();
        const task = data.data && data.data.length > 0 ? data.data[0] : null;
        if (!task) { hideLoader(); showToast('Task not found', 'error'); return; }

        document.getElementById('editTaskId').value = task.id;
        document.getElementById('editTaskTitle').value = task.title;
        document.getElementById('editTaskDescription').value = task.description || '';
        document.getElementById('editTaskAssignedTo').value = task.assigned_to;
        document.getElementById('editTaskPriority').value = task.priority;
        document.getElementById('editTaskStatus').value = task.status;

        const dt = new Date(task.due_date);
        const dateStr = dt.toISOString().split('T')[0];
        const timeStr = dt.toTimeString().slice(0, 5);
        document.getElementById('editTaskDueDate').value = dateStr;
        document.getElementById('editTaskDueTime').value = timeStr === '23:59' ? '' : timeStr;

        document.querySelectorAll('#editTaskForm input, #editTaskForm select, #editTaskForm textarea, #editTaskForm button[type="submit"]').forEach(el => {
            el.disabled = false;
        });

        document.getElementById('taskModal').classList.remove('hidden');
    } catch (_) {
        showToast('Failed to load task', 'error');
    } finally {
        hideLoader();
    }
}

function closeTaskModal() {
    document.getElementById('taskModal').classList.add('hidden');
}

document.getElementById('editTaskForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    showLoader();
    const id = document.getElementById('editTaskId').value;
    const title = document.getElementById('editTaskTitle').value;
    const assignedTo = document.getElementById('editTaskAssignedTo').value;
    const priority = document.getElementById('editTaskPriority').value;
    const status = document.getElementById('editTaskStatus').value;
    const dateVal = document.getElementById('editTaskDueDate').value;
    const timeVal = document.getElementById('editTaskDueTime').value;
    const description = document.getElementById('editTaskDescription').value;
    const body = `id=${id}&title=${encodeURIComponent(title)}&assigned_to=${assignedTo}&priority=${priority}&status=${status}&due_date=${encodeURIComponent(timeVal ? dateVal + ' ' + timeVal + ':00' : dateVal + ' 23:59:59')}&description=${encodeURIComponent(description)}`;
    try {
        const res = await fetch('../api/tasks.php', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body,
        });
        const data = await res.json();
        if (data.success) { showToast('Task updated successfully'); closeTaskModal(); location.reload(); }
        else { hideLoader(); showToast(data.message, 'error'); }
    } catch (err) { hideLoader(); showToast('Network error', 'error'); }
});

// ─── ACTIVITY NOTIFICATION ────────────────────────────
let lastActivityMtime = 0;

document.getElementById('navActivity')?.addEventListener('click', function () {
    const badge = document.getElementById('activityBadge');
    badge.classList.add('hidden');
});

// ─── AUTO-REFRESH ───────────────────────────────────
let refreshInterval;
let lastTaskMtime = 0;
let lastStatsMtime = 0;

async function getTaskMtime() {
    try {
        const res = await fetch('../api/last_update.php');
        return parseInt(await res.text(), 10) || 0;
    } catch (_) { return 0; }
}

async function getActivityMtime() {
    try {
        const res = await fetch('../api/activity_status.php');
        return parseInt(await res.text(), 10) || 0;
    } catch (_) { return 0; }
}

function startAutoRefresh() {
    if (refreshInterval) clearInterval(refreshInterval);
    refreshInterval = setInterval(async () => {
        let changed = false;
        const taskMtime = await getTaskMtime();
        if (taskMtime > lastTaskMtime) {
            lastTaskMtime = taskMtime;
            loadTasks(true);
            changed = true;
        }
        const actMtime = await getActivityMtime();
        if (actMtime > lastStatsMtime) {
            lastStatsMtime = actMtime;
            if (!document.getElementById('sectionActivity').classList.contains('hidden')) loadActivity(true);
            changed = true;
        }
        if (actMtime > lastActivityMtime) {
            lastActivityMtime = actMtime;
            const section = document.getElementById('sectionActivity');
            if (section && section.classList.contains('hidden')) {
                document.getElementById('activityBadge').classList.remove('hidden');
            }
        }
        if (changed) updateStats();
        if (!document.getElementById('sectionStaff').classList.contains('hidden')) loadStaff(true);
        if (!document.getElementById('sectionDepartments').classList.contains('hidden')) loadDepts(true);
    }, 10000);
}

async function updateStats() {
    try {
        const res = await fetch('../api/stats.php');
        const data = await res.json();
        if (!data.success) return;
        const s = data.data;
        const cards = document.querySelectorAll('.stat-card p.text-3xl');
        if (cards.length >= 5) {
            cards[0].textContent = s.total_staff;
            cards[1].textContent = s.total_tasks;
            cards[2].textContent = s.pending;
            cards[3].textContent = s.in_progress;
            cards[4].textContent = s.completed;
        }
    } catch (_) {}
}

function stopAutoRefresh() {
    if (refreshInterval) clearInterval(refreshInterval);
}

document.addEventListener('visibilitychange', () => {
    if (document.hidden) stopAutoRefresh();
    else startAutoRefresh();
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

if (!document.hidden) startSessionCheck();
document.addEventListener('visibilitychange', () => {
    if (document.hidden) stopSessionCheck();
    else { checkSession(); startSessionCheck(); }
});

// ─── STAFF ACTIVITY ─────────────────────────────────
const activityTableBody = document.getElementById('activityTableBody');

function applyActivityFilter() { loadActivity(); }

async function loadActivity(silent) {
    if (!silent) showLoader();
    const params = new URLSearchParams();
    const staffId = document.getElementById('activityFilterStaff').value;
    if (staffId) params.set('staff_id', staffId);
    const dateFrom = document.getElementById('activityDateFrom').value;
    if (dateFrom) params.set('date_from', dateFrom);
    const dateTo = document.getElementById('activityDateTo').value;
    if (dateTo) params.set('date_to', dateTo);
    try {
        const res = await fetch(`../api/work_logs.php?${params}`);
        const data = await res.json();
        if (!data.success) throw new Error(data.message);
        if (data.data.length === 0) {
            activityTableBody.innerHTML = '<tr><td colspan="5" class="text-center text-[var(--text-muted)] py-8">No activity logged yet</td></tr>';
            return;
        }
        activityTableBody.innerHTML = data.data.map(w => `
            <tr>
                <td class="font-medium">${escHtml(w.staff_name)}</td>
                <td class="text-[var(--text-secondary)]">${escHtml(w.department || '—')}</td>
                <td class="text-[var(--text-secondary)] max-w-xs">${escHtml(w.description)}</td>
                <td class="text-[var(--text-secondary)] whitespace-nowrap">${w.log_date}</td>
                <td class="text-[var(--text-muted)] text-xs whitespace-nowrap">${new Date(w.created_at).toLocaleString()}</td>
                <td>
                    <button onclick="openWorkLogReply(${w.id})" class="text-xs text-purple-500 hover:text-purple-400 transition font-medium whitespace-nowrap">
                        ${parseInt(w.reply_count) > 0 ? `Reply (${w.reply_count})` : 'Reply'}
                    </button>
                </td>
            </tr>
        `).join('');
    } catch (err) {
        activityTableBody.innerHTML = '<tr><td colspan="5" class="text-center text-red-500 py-8">Failed to load activity</td></tr>';
    } finally {
        if (!silent) hideLoader();
    }
}

// ─── WORK LOG REPLY ──────────────────────────────────
let replyAutoRefresh = null;
let lastReplyCount = -1;
let replyLastTs = 0;

async function openWorkLogReply(workLogId) {
    document.getElementById('replyWorkLogId').value = workLogId;
    document.getElementById('replyMessage').value = '';
    document.getElementById('workLogReplyModal').classList.remove('hidden');
    document.getElementById('workLogReplyThread').innerHTML = '<p class="text-xs text-[var(--text-muted)] text-center py-4">Loading...</p>';
    lastReplyCount = -1;

    try {
        const [logRes, repliesRes] = await Promise.all([
            fetch(`../api/work_logs.php?id=${workLogId}`),
            fetch(`../api/work_log_replies.php?work_log_id=${workLogId}`)
        ]);

        const logData = await logRes.json();
        const log = logData.data && logData.data.length > 0 ? logData.data[0] : null;
        if (log) {
            document.getElementById('workLogReplyOriginal').innerHTML = `
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium">${escHtml(log.staff_name)}</p>
                        <p class="text-sm text-[var(--text-secondary)] mt-1 whitespace-pre-wrap">${escHtml(log.description)}</p>
                        <p class="text-xs text-[var(--text-muted)] mt-2">${log.log_date} &middot; ${new Date(log.created_at).toLocaleString()}</p>
                    </div>
                </div>
            `;
        }

        const repliesData = await repliesRes.json();
        renderAdminReplies(repliesData, true);
    } catch (_) {
        document.getElementById('workLogReplyThread').innerHTML = '<p class="text-xs text-red-500 text-center py-4">Failed to load</p>';
    }

    try {
        const tr = await fetch('../api/activity_status.php');
        replyLastTs = parseInt(await tr.text(), 10) || 0;
    } catch (_) { replyLastTs = 0; }

    startReplyAutoRefresh(workLogId);
}

function closeWorkLogReplyModal() {
    document.getElementById('workLogReplyModal').classList.add('hidden');
    stopReplyAutoRefresh();
    lastReplyCount = -1;
}

function scrollReplyToBottom() {
    const container = document.getElementById('workLogReplyModal').querySelector('.max-h-\\[90vh\\]');
    if (container) {
        requestAnimationFrame(() => { container.scrollTop = container.scrollHeight; });
    }
}

function renderAdminReplies(data, force) {
    if (!data.success) return;
    if (!force && data.data.length === lastReplyCount) return;
    lastReplyCount = data.data.length;
    const container = document.getElementById('workLogReplyThread');
    if (data.data.length === 0) {
        container.innerHTML = '<p class="text-xs text-[var(--text-muted)] text-center py-4">No replies yet</p>';
        return;
    }
    const wasNearBottom = container && (container.scrollHeight - container.scrollTop - container.clientHeight) < 100;
    container.innerHTML = data.data.map(r => `
        <div class="flex ${r.user_role === 'admin' ? 'justify-end' : 'justify-start'}">
            <div class="max-w-[85%] p-3 rounded-2xl border" style="background:${r.user_role === 'admin' ? 'rgba(147,51,234,0.15)' : 'var(--bg-card)'}; border-color:${r.user_role === 'admin' ? 'rgba(147,51,234,0.3)' : 'var(--border-color)'};">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-medium">${escHtml(r.user_name)}</span>
                    <span class="text-xs px-1.5 py-0.5 rounded-full ${r.user_role === 'admin' ? 'bg-purple-500/20 text-purple-400' : 'bg-blue-500/20 text-blue-400'}">${r.user_role}</span>
                </div>
                <p class="text-sm whitespace-pre-wrap">${escHtml(r.message)}</p>
                <p class="text-xs text-[var(--text-muted)] mt-1">${new Date(r.created_at).toLocaleString()}</p>
            </div>
        </div>
    `).join('');
    if (force || wasNearBottom) scrollReplyToBottom();
}

async function loadWorkLogReplies(workLogId) {
    try {
        const res = await fetch(`../api/work_log_replies.php?work_log_id=${workLogId}`);
        const data = await res.json();
        renderAdminReplies(data, false);
    } catch (_) {}
}

function startReplyAutoRefresh(workLogId) {
    stopReplyAutoRefresh();
    replyAutoRefresh = setInterval(async () => {
        try {
            const res = await fetch('../api/activity_status.php');
            const ts = parseInt(await res.text(), 10);
            if (ts > replyLastTs) {
                replyLastTs = ts;
                await loadWorkLogReplies(workLogId);
            }
        } catch (_) {}
    }, 2000);
}

function stopReplyAutoRefresh() {
    if (replyAutoRefresh) {
        clearInterval(replyAutoRefresh);
        replyAutoRefresh = null;
    }
}

document.getElementById('workLogReplyForm')?.addEventListener('submit', async function (e) {
    e.preventDefault();
    const workLogId = document.getElementById('replyWorkLogId').value;
    const message = document.getElementById('replyMessage').value.trim();
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
            document.getElementById('replyMessage').value = '';
            lastReplyCount = -1;
            replyLastTs = Date.now() / 1000;
            await loadWorkLogReplies(workLogId);
        } else {
            showToast(data.message, 'error');
        }
    } catch (err) {
        showToast('Network error', 'error');
    } finally {
        btn.disabled = false;
    }
});

document.getElementById('replyMessage')?.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        document.getElementById('workLogReplyForm')?.requestSubmit();
    }
});

document.getElementById('workLogReplyModal')?.addEventListener('click', function (e) {
    if (e.target === this) closeWorkLogReplyModal();
});

// ─── NOTE MODAL ──────────────────────────────────────
function showNoteModal(btn) {
    const note = typeof btn === 'string' ? btn : btn.dataset.note;
    document.getElementById('noteModalContent').textContent = note;
    document.getElementById('noteModal').classList.remove('hidden');
}

function closeNoteModal() {
    document.getElementById('noteModal').classList.add('hidden');
}

document.getElementById('noteModal')?.addEventListener('click', function (e) {
    if (e.target === this) closeNoteModal();
});

// ─── UTILITY ─────────────────────────────────────────
function escHtml(str) {
    const div = document.createElement('div');
    div.textContent = str || '';
    return div.innerHTML;
}

function escHtmlAttr(str) {
    return (str || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/\n/g, '\\n').replace(/\r/g, '').replace(/\//g, '\\/');
}

// ─── INIT ────────────────────────────────────────────
const savedSection = sessionStorage.getItem('admin_section');
if (savedSection && savedSection !== 'dashboard') {
    switchSection(savedSection);
} else {
    loadTasks();
}
updateStats();
Promise.all([getTaskMtime(), getActivityMtime()]).then(([tm, am]) => {
    lastTaskMtime = tm;
    lastStatsMtime = am;
    lastActivityMtime = am;
});
startAutoRefresh();
