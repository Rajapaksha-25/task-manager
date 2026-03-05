<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Register — TaskFlow</title>
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
    <h1 class="title">Create Account</h1>
    <p class="sub">
      Already have an account? <a href="/login">Sign in</a>
    </p>

    <!-- Full Name -->
    <div class="field">
      <label class="lbl" for="name">Full Name</label>
      <input id="name" class="inp" type="text" placeholder="Pasindu Silva">
    </div>

    <!-- Email -->
    <div class="field">
      <label class="lbl" for="email">Email Address</label>
      <input id="email" class="inp" type="email" placeholder="you@example.com">
    </div>

    <!-- Passwords -->
    <div class="field-row">
      <div class="field">
        <label class="lbl" for="password">Password</label>
        <input id="password" class="inp" type="password" placeholder="Min 6 chars">
      </div>

      <div class="field">
        <label class="lbl" for="password_confirmation">Confirm Password</label>
        <input id="password_confirmation" class="inp" type="password" placeholder="Repeat password">
      </div>
    </div>

    <!-- Register Button -->
    <button type="button" class="btn" onclick="register()">
      Create Account
    </button>

    <!-- Terms -->
    <p class="terms">
      By registering, you agree to our Terms & Privacy Policy.
    </p>

  </div>

</div>

</body>
</html>