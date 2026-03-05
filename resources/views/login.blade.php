<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Login — TaskFlow</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body>

<div class="page">

  <div class="card">

    <!-- Logo -->
    <div class="logo">
      <div class="logo-mark">✦</div>
      <span class="logo-name">TaskFlow</span>
    </div>

    <!-- Title -->
    <h1 class="title">Sign In</h1>
    <p class="sub">
      Don't have an account? <a href="/register">Register</a>
    </p>

    <!-- Email -->
    <div class="field">
      <label class="lbl" for="email">Email Address</label>
      <input id="email" class="inp" type="email" placeholder="you@example.com">
      <div id="error-email" class="error"></div>
    </div>

    <!-- Password -->
    <div class="field">
      <label class="lbl" for="password">Password</label>
      <input id="password" class="inp" type="password" placeholder="Enter your password">
      <div id="error-password" class="error"></div>
    </div>

    <!-- Login Button -->
    <button type="button" class="btn" onclick="login()">
      Sign In
    </button>

    <!-- Terms -->
    <p class="terms">
      By signing in, you agree to our Terms & Privacy Policy.
    </p>

  </div>

</div>

</body>
</html>