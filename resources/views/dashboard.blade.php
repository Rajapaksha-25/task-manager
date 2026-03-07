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

  <!-- stat cards -->
    <div class="stats-row">
      <div class="stat-card s-total">
        <div class="stat-num" id="stat-total">0</div>
        <div class="stat-label">Total</div>
      </div>
      <div class="stat-card s-pending">
        <div class="stat-num" id="stat-pending">0</div>
        <div class="stat-label">Pending</div>
      </div>  
      <div class="stat-card s-progress">
        <div class="stat-num" id="stat-progress">0</div>
        <div class="stat-label">Progress</div>
      </div>  
      <div class="stat-card s-done">
        <div class="stat-num" id="stat-done">0</div>
        <div class="stat-label">Completed</div>
      </div> 
    </div>

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;flex-wrap:wrap;gap:10px">
      <div class="view-tabs">
        <button class="tab-btn active" id="tab-active" onclick="switchView('active')">Active Task</button>
        <button class="tab-btn" id="tab-trashed" onclick="switchView('trashed')"> Trash</button>
      </div>  
      <button class="btn-add" id="btn-new-task" onclick="openModal()">+ New Task</button>
    </div>

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
          <option value="due_date|asc">Due Date Up</option>
          <option value="due_date|desc">Due Date Down</option>
       </select>
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
    <div class="modal-title" id="modal-title">New Task</div>
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

<!-- VIEW TASK MODAL -->
<div class="modal-overlay" id="view-modal" style="display:none" onclick="if(event.target===this)closeViewModal()">
  <div class="view-modal-box">
    <button class="modal-close" onclick="closeViewModal()">x</button>
    <div class="modal-title" id="view-title"></div>
    <hr class="view-divider">

    <div class="view-field">
      <div class="view-field-label">Description</div>
      <div class="view-field-value" id="view-desc">-</div>
    </div>

    <div class="field-row">
      <div class="view-field">
        <div class="view-field-label">Status</div>
        <div class="view-field-value" id="view-status"></div>
      </div>
      <div class="view-field">
        <div class="view-field-label">Priority</div>
        <div class="view-field-value" id="view-priority"></div>
      </div>
    </div>

    <div class="field-row">
      <div class="view-field">
        <div class="view-field-label">Due Date</div>
        <div class="view-field-value" id="view-due">-</div>
      </div>
      <div class="view-field">
        <div class="view-field-label">Created</div>
        <div class="view-field-value" id="view-created"></div>
      </div>
    </div>

    <div class="modal-footer">
      <button class="btn-ghost" onclick="closeViewModal()">Close</button>
      <button class="btn" id="view-edit-btn" onclick="" style="margin-top:0">Edit</button>
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