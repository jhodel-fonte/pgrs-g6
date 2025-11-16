<?php
// login.php
session_start();

// Initialize login attempts if not set
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt'] = time();
}

// Generate CAPTCHA numbers if needed
if ($_SESSION['login_attempts'] >= 3) {
    if (!isset($_SESSION['captcha_a']) || !isset($_SESSION['captcha_b'])) {
        $_SESSION['captcha_a'] = rand(1, 9);
        $_SESSION['captcha_b'] = rand(1, 9);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Padre Garcia Reporting System - Login</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

  <div class="login-bg">
    <div class="overlay"></div>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">

      <div class="login-card p-5 text-center shadow-lg">
        <a href="index.php"><img src="assets/img/logo.png" alt="UNITY PGSRS Logo" class="mb-2" style="width: 80px;"></a>
        <h3 class="fw-bold text">Padre Garcia Service Report System</h3>

        <form action="login_process.php" method="POST">

          <!-- Username -->
          <div class="mb-3 text-start">
            <label class="form-label text-light">Username</label>
            <input type="text" name="username" class="form-control form-control-lg" required>
          </div>

          <!-- Password -->
          <div class="mb-4 text-start">
            <label class="form-label text-light">Password</label>
            <input type="password" name="password" class="form-control form-control-lg" required>
          </div>

          <!-- CAPTCHA (only shows after 3 failed attempts) -->
          <?php if ($_SESSION['login_attempts'] >= 3): ?>
          <div class="mb-4 text-start">
            <label class="form-label text-light fw-bold">Security Check</label>
            <div class="p-3 rounded bg-dark text-light border border-secondary mb-2">
              <span class="fs-5">
                <?= (int)$_SESSION['captcha_a'] ?> + <?= (int)$_SESSION['captcha_b'] ?> = ?
              </span>
            </div>
            <input type="number" name="captcha_answer" class="form-control form-control-lg" placeholder="Enter the answer" required>
            <small class="text-warning">CAPTCHA visible due to multiple failed login attempts.</small>
          </div>
          <?php endif; ?>

          <!-- Login Button -->
          <button type="submit" class="btn w-100 py-2">Login</button>

          <!-- Links -->
          <div class="mt-3">
            <a href="forgot_password.php" class="link text-decoration-none me-3">Forgot Password?</a>
            <a href="register.php" class="link text-decoration-none">Create Account</a>
          </div>

        </form>
      </div>

    </div>
  </div>

</body>
</html>
