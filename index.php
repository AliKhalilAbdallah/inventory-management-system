<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Inventory System - Login</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4" style="width: 400px;">
      <div class="text-center mb-4">
        <img src="assets/img/logo.png" alt="Logo" width="60">
        <h3 class="mt-2">Inventory Management System</h3>
      </div>

      <!-- Login Form -->
      <form action="actions/login.php" method="POST">
        <div class="mb-3">
          <label for="email" class="form-label">username</label>
          <input type="text" class="form-control" name="username" placeholder="Enter username" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" name="password" placeholder="Enter password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>

      <!-- Register Link -->
      <div class="text-center mt-3">
        <small>Don’t have an account? <a href="register.html">Register</a></small>
      </div>
    </div>
  </div>

  <script src="assets/js/main.js"></script>
</body>
</html>
