<?php
session_start();

// var_dump($_SESSION['user']) ;
// var_dump($_SESSION['isOtpVerified']) ;
// echo '1';
if (isset($_SESSION['user']) && isset($_SESSION['isValid']) && $_SESSION['isValid'] === 1 && isset($_SESSION['isOtpVerified']) && $_SESSION['isOtpVerified'] === 1) {
    //aready login so it will redirect to main



    header("Location: ../public/user/user_dashboard.php");
    exit;
}

/* if (isset($_SESSION['user']) && (!isset($_SESSION['isOtpVerified']) || $_SESSION['isOtpVerified'] === 0)) {
    //redirect to otp if not verified
    echo 'Must not trigger Beacuse it is for manual inster of link';
    header("Location: ../public/otp.php");
    exit;
} */

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
  <link rel="stylesheet" href="./assets/style.css">
</head>
<body>

  <div class="login-bg">
    <div class="overlay"></div>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">

      <div class="login-card p-5 text-center shadow-lg">
        <a href="index.php"><img src="assets/img/logo.png" alt="UNITY PGSRS Logo" class="mb-2" style="width: 80px;"></a>
        <h3 class="fw-bold text">Padre Garcia Service Report System</h3>

        <form action="../request/auth.php?login=1" method="POST">

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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        if (performance.navigation.type === 1) {
            window.location.href = "login.php";
        }     
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const loginForm = document.getElementById('loginForm');
            const errorMessageDiv = document.getElementById('error-message');

            loginForm.addEventListener('submit', async (event) => {
                event.preventDefault();//prevent reload
                // errorMessageDiv.textContent = '';//handle previous message

                try {
                    //get the data
                    const response = await fetch('../request/auth.php?login=1', {
                        method: 'POST',
                        body: new FormData(loginForm)
                    });

                    const data = await response.json();//get response to js

                    //process 
                    if (data.response === 'error') {
                        // errorMessageDiv.textContent = `Login Error: ${data.message}`;
                        Swal.fire({
                            icon: 'error',
                            title: 'Server Error',
                            text: `HTTP Error: ${response.status}`,
                        });
                        // return;
                    } else {
                        console.log('Login successful, processing...'); //in our case the auth.php redirects but in case i leave it here
                    }

                } catch (error) {
                    //other error handling
                    console.error('Fetch error:', error);
                    errorMessageDiv.textContent = 'A network error occurred. Please try again.';
                }
            });
        });
    </script>

    <?php 
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['error'])){
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Login Error',
                    text: '" . htmlspecialchars($_GET['error'], ENT_QUOTES) . "', 
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.history.pushState(null, '', 'login.php');
                    }
                });
            </script>";
        }
    ?>

    
</body>
</html>