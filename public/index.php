<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dolphin CRM Login</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex flex-column min-vh-100">

<!-- Top Navbar -->
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand mb-0 h1">Dolphin CRM</span>
  </div>
</nav>

<!-- Login Card -->
<div class="container flex-grow-1 d-flex justify-content-center align-items-center">
  <div class="card p-4" style="width: 380px;">
    <h5 class="text-center mb-4">Login</h5>

    <form method="POST" action="../src/controllers/LoginController.php">
      <div class="mb-3">
        <input
          type="email"
          name="email"
          class="form-control"
          placeholder="Email address"
          required
        >
      </div>

      <div class="mb-3">
        <input
          type="password"
          name="password"
          class="form-control"
          placeholder="Password"
          required
        >
      </div>

      <button type="submit" class="btn btn-primary w-100">
        Login
      </button>
    </form>
  </div>
</div>

<!-- Footer -->
<footer class="text-center text-muted py-3">
  <small>Copyright © 2022 Dolphin CRM</small>
</footer>

</body>
</html>
