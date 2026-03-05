<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Home — TaskFlow</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css'])
</head>
<body>

<div class="page">

  <div class="card" style="text-align:center; padding:50px 30px; max-width:400px;">

    <!-- Logo -->
    <div class="logo" style="justify-content:center; margin-bottom:30px;">
      <div class="logo-mark">✦</div>
      <span class="logo-name">TaskFlow</span>
    </div>

    <!-- Welcome Message -->
    <h1 class="title" style="margin-bottom:20px;">Welcome to Task Manager</h1>
    <p class="sub" style="margin-bottom:30px;">
      Manage your tasks efficiently and stay productive.
    </p>

    <!-- Buttons -->
    <div style="display:flex; flex-direction:column; gap:12px;">
      <a href="/login" class="btn" style="text-decoration:none; display:block; text-align:center;">Login</a>
      <a href="/register" class="btn" style="text-decoration:none; display:block; text-align:center;">Register</a>
    </div>

  </div>

</div>

</body>
</html>