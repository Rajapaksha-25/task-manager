<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Dashboard — TaskFlow</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body>

<div class="dash-page">

  <!-- NAVBAR -->
  <nav class="navbar">
    <div class="nav-left">
      <div class="logo-mark">✦</div>
      <span class="logo-name">TaskFlow</span>
    </div>
    <div style="display:flex;align-items:center;gap:14px">
      <span class="nav-user">Hello, <span id="user-name">...</span></span>
      <button class="btn-sm" onclick="logout()">Sign Out</button>
    </div>
  </nav>

  <!-- CONTENT -->
  <div class="dash-content">

    <!-- TOOLBAR -->
    <div class="toolbar" id="toolbar">
      <div class="toolbar-search">
        <input class="inp-sm" id="search" type="text"
        placeholder="Search tasks…" oninput="debounceSearch()"/>
      </div>
      <select class="inp-sm" id="filter-status" onchange="loadTasks(1)" style="width:auto">
        <option value="">All Statuses</option>
        <option value="pending">Pending</option>
        <option value="in_progress">In Progress</option>
        <option value="completed">Completed</option>
      </select>
      <select class="inp-sm" id="filter-priority" onchange="loadTasks(1)" style="width:auto">
           <option value="">All Priorities</option>
           <option value="low">Low</option>
           <option value="medium">Medium</option>
           <option value="high">High</option>
       </select>
       <select class="inp-sm" id="sort" onchange="loadTasks(1)" style="width:auto">
          <option value="created_at|desc">Newest First</option>
          <option value="created_at|asc">Oldest First</option>
          <option value="due_date|asc">Due Date ↑</option>
          <option value="due_date|desc">Due Date ↓</option>
       </select>
       <button class="btn-add" onclick="openModal()">+ New Task</button>
    </div>

    <!-- TASK LIST -->
    <div class="task-list" id="task-list"></div>
    

    <!-- PAGINATION -->
    <div class="pagination" id="pagination"></div>

  </div>
</div>

<!-- TASK MODAL -->
<div class="modal-overlay" id="task-modal" style="display:none" onclick="if(event.target===this)closeModal()">
  <div class="modal-box">
    <button class="modal-close" onclick="closeModal()">✕</button>
    <div class="modal-title">New Task</div>
    <div class="error" id="modal-err" style="margin-bottom:12px"></div>

    <div class="field">
      <label class="lbl">Title *</label>
      <input class="inp" type="text" id="m-title" placeholder="What needs to be done?"/>
    </div>
    <div class="field">
      <label class="lbl">Description</label>
      <textarea class="inp" id="m-desc" placeholder="Add details…"
        style="resize:vertical;min-height:72px"></textarea>
    </div>
    <div class="field-row">
      <div class="field">
        <label class="lbl">Status</label>
        <select class="inp" id="m-status">
          <option value="pending">Pending</option>
          <option value="in_progress">In Progress</option>
          <option value="completed">Completed</option>
        </select>
      </div>
      <div class="field">
        <label class="lbl">Priority</label>
        <select class="inp" id="m-priority">
          <option value="low">Low</option>
          <option value="medium" selected>Medium</option>
          <option value="high">High</option>
        </select>
      </div>
    </div>
    <div class="field">
      <label class="lbl">Due Date</label>
      <input class="inp" type="date" id="m-due"/>
    </div>

    <div class="modal-footer">
      <button class="btn-ghost" onclick="closeModal()">Cancel</button>
      <button class="btn" id="modal-submit" onclick="submitTask()" style="margin-top:0">
        Create Task
      </button>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    checkAuth();
    loadDashboard();
  });
</script>
</body>
</html>