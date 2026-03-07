import './bootstrap';

const API = "http://127.0.0.1:8000/api";

// REGISTER 
async function register() {
    ["name","email","password","password_confirmation"].forEach(id => {
        const el = document.getElementById(`error-${id}`);
        if (el) el.textContent = "";
    });

    const name                 = document.getElementById("name").value;
    const email                = document.getElementById("email").value;
    const password             = document.getElementById("password").value;
    const password_confirmation = document.getElementById("password_confirmation").value;

    const res  = await fetch(API + "/register", {
        method: "POST",
        headers: { "Content-Type": "application/json", "Accept": "application/json" },
        body: JSON.stringify({ name, email, password, password_confirmation })
    });
    const data = await res.json();

    if (res.ok) {
        localStorage.setItem("token", data.access_token);
        localStorage.setItem("user",  JSON.stringify(data.user));
        window.location.href = "/dashboard";
    } else {
        if (data.errors) {
            for (const key in data.errors) {
                const el = document.getElementById(`error-${key}`);
                if (el) el.textContent = data.errors[key][0];
            }
        } else if (data.message) {
            const el = document.getElementById("error-password");
            if (el) el.textContent = data.message;
        }
    }
}

// LOGIN 
async function login() {
    ["email","password"].forEach(id => {
        const el = document.getElementById(`error-${id}`);
        if (el) el.textContent = "";
    });

    const email    = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    const res  = await fetch(API + "/login", {
        method: "POST",
        headers: { "Content-Type": "application/json", "Accept": "application/json" },
        body: JSON.stringify({ email, password })
    });
    const data = await res.json();

    if (res.ok) {
        localStorage.setItem("token", data.access_token);
        localStorage.setItem("user",  JSON.stringify(data.user));
        window.location.href = "/dashboard";
    } else {
        if (data.errors) {
            for (const key in data.errors) {
                const el = document.getElementById(`error-${key}`);
                if (el) el.textContent = data.errors[key][0];
            }
        } else if (data.message) {
            const el = document.getElementById("error-password");
            if (el) el.textContent = data.message;
        }
    }
}

//  LOGOUT 
function logout() {
    localStorage.removeItem("token");
    localStorage.removeItem("user");
    window.location.href = "/login";
}

//  AUTH CHECK 
function checkAuth() {
    if (!localStorage.getItem("token")) {
        window.location.href = "/login";
    }
}

//  DASHBOARD 

async function loadDashboard() {
    checkAuth();
    const user   = JSON.parse(localStorage.getItem('user') || '{}');
    const nameEl = document.getElementById('user-name');
    if (nameEl) nameEl.textContent = user.name || 'User';
    await loadTasks();
}

let currentPage = 1;
let searchTimer = null;
let editingId   = null; // tracks which task is being edited

async function loadTasks(page = 1) {
    currentPage = page;
    const token = localStorage.getItem('token');
    const list  = document.getElementById('task-list');
    if (!list) return;

    list.innerHTML = `<div style="padding:30px;text-align:center;color:var(--muted);font-size:13px">Loading…</div>`;

    const sort = (document.getElementById('sort')?.value || 'created_at|desc').split('|');

    const params = new URLSearchParams({
        page,
        per_page: 10,
        status:   document.getElementById('filter-status')?.value  || '',
        priority: document.getElementById('filter-priority')?.value || '',
        search:   document.getElementById('search')?.value          || '',
        sort_by: sort[0],
        sort_dir: sort[1],
    });

    try {
        const res  = await fetch(`${API}/tasks?${params}`, {
            headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' }
        });
        const data = await res.json();
        renderTasks(data.data || []);
        renderPagination(data);
    } catch(e) {
        list.innerHTML = `<div class="empty-state">
          <div class="empty-title">Failed to load</div>
          <div class="empty-sub">Check your connection.</div>
        </div>`;
    }
}

function renderTasks(tasks) {
    const list = document.getElementById('task-list');
    const today = new Date().toISOString().split('T')[0];
    if (!tasks.length) {
        list.innerHTML = `<div class="empty-state">
          <div class="empty-icon">✓</div>
          <div class="empty-title">No tasks yet</div>
          <div class="empty-sub">Click "+ New Task" to get started.</div>
        </div>`;
        return;
    }

    const sLabel = { pending: 'Pending', in_progress: 'In Progress', completed: 'Done' };
    const sClass = { pending: 'badge-pending', in_progress: 'badge-progress', completed: 'badge-done' };
    const pClass = { low: 'badge-low', medium: 'badge-medium', high: 'badge-high' };

    list.innerHTML = tasks.map(t => `
        <div class="task-item ${t.status === 'completed' ? 'done-item' : ''}">
          <div class="task-body">
            <div class="task-title-text">${esc(t.title)}</div>
            <div class="task-meta-row">
              <span class="badge ${sClass[t.status] || ''}">${sLabel[t.status] || t.status}</span>
              <span class="badge ${pClass[t.priority] || ''}">${t.priority}</span>
              ${t.due_date ? `<span class="task-due ${t.due_date < today && t.status !== 'completed' ? 'overdue' : ''}">${t.due_date}</span>` : ''}
            </div>
          </div>
          <div class="task-actions">
            <button class="act-btn" onclick='openEditModal(${JSON.stringify(t)})'>Edit</button>
            <button class="act-btn danger" onclick="deleteTask(${(t.id)})">Delete</button>
          </div>
        </div>

    `).join('');
}

function renderPagination(data) {
    const wrap = document.getElementById('pagination');
    if (!wrap) return;
    if (!data.last_page || data.last_page <= 1) { wrap.innerHTML = ''; return; }

    let html = `<button class="pg-btn" onclick="loadTasks(${data.current_page - 1})"
        ${data.current_page <= 1 ? 'disabled' : ''}>‹</button>`;

    for (let i = 1; i <= data.last_page; i++) {
        if (i === 1 || i === data.last_page || Math.abs(i - data.current_page) <= 1)
            html += `<button class="pg-btn ${i === data.current_page ? 'active' : ''}"
                onclick="loadTasks(${i})">${i}</button>`;
        else if (Math.abs(i - data.current_page) === 2)
            html += `<span class="pg-info">…</span>`;
    }

    html += `<button class="pg-btn" onclick="loadTasks(${data.current_page + 1})"
        ${data.current_page >= data.last_page ? 'disabled' : ''}>›</button>`;
    html += `<span class="pg-info">${data.from}–${data.to} of ${data.total}</span>`;
    wrap.innerHTML = html;
}

// ---- MODAL ----
function openModal() {
    editingId = null;
    document.getElementById('modal-title').textContent  = 'New Task';
    document.getElementById('modal-submit').textContent = 'Create Task';
    document.getElementById('m-title').value        = '';
    document.getElementById('m-desc').value         = '';
    document.getElementById('m-status').value       = 'pending';
    document.getElementById('m-priority').value     = 'medium';
    document.getElementById('m-due').value          = '';
    document.getElementById('modal-err').textContent = '';
    document.getElementById('task-modal').style.display = 'flex';
}

function openEditModal(task) {
    editingId = task.id;
    document.getElementById('modal-title').textContent  = 'Edit Task';
    document.getElementById('modal-submit').textContent = 'Save Changes';
    document.getElementById('m-title').value            = task.title       || '';
    document.getElementById('m-desc').value             = task.description || '';
    document.getElementById('m-status').value           = task.status      || 'pending';
    document.getElementById('m-priority').value         = task.priority    || 'medium';
    document.getElementById('m-due').value              = task.due_date    || '';
    document.getElementById('modal-err').textContent    = '';
    document.getElementById('task-modal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('task-modal').style.display = 'none';
}

async function submitTask() {
    const token = localStorage.getItem('token');
    const btn   = document.getElementById('modal-submit');
    const errEl = document.getElementById('modal-err');
    errEl.textContent = '';

    const body = {
        title:       document.getElementById('m-title').value.trim(),
        description: document.getElementById('m-desc').value.trim(),
        status:      document.getElementById('m-status').value,
        priority:    document.getElementById('m-priority').value,
        due_date:    document.getElementById('m-due').value || null,
    };

    if (!body.title) { errEl.textContent = 'Title is required.'; return; }

    const orig = btn.textContent;
    btn.disabled = true; btn.textContent = 'Saving…';

    try {
        const res  = await fetch(editingId ? `${API}/tasks/${editingId}` : `${API}/tasks`, {
            method: editingId ? 'PUT' : 'POST',
            headers: { 'Content-Type': 'application/json', Accept: 'application/json', Authorization: `Bearer ${token}` },
            body:    JSON.stringify(body),
        });
        const data = await res.json();
        if (!res.ok) { errEl.textContent = data.message || 'Failed.'; return; }
        closeModal();
        toast(editingId ? 'Task updated.' : 'Task created!', 'ok');
        loadTasks(currentPage);
    } catch(e) {
        errEl.textContent = 'Connection error.';
    } finally {
        btn.disabled = false; btn.textContent = orig;
    }
}

async function deleteTask(id) {
    if (!confirm('Move this task to trash?')) return;
    const token = localStorage.getItem('token');

    try {
        await fetch(`${API}/tasks/${id}`, {
            method: 'DELETE',
            headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' }
        });
        toast('Task moved to trash.', 'ok');
        loadTasks(currentPage);
    } catch(e) {
        toast('Failed to delete task.', 'err');
    }
}

function debounceSearch() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => loadTasks(1), 350);
}

//  HELPERS 
function esc(str) {
    return String(str)
        .replace(/&/g,'&amp;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;');
}

function toast(msg, type = 'ok') {
    const el = document.createElement('div');
    el.className = `toast ${type}`;
    el.innerHTML = `<span>${type === 'ok' ? '✓' : '✕'}</span> ${msg}`;
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 3000);
}

//  EXPOSE TO WINDOW 
window.register       = register;
window.login          = login;
window.logout         = logout;
window.checkAuth      = checkAuth;
window.loadDashboard  = loadDashboard;
window.loadTasks      = loadTasks;
window.openModal      = openModal;
window.closeModal     = closeModal;
window.submitTask     = submitTask;
window.debounceSearch = debounceSearch;
window.openEditModal = openEditModal;
window.deleteTask    = deleteTask;