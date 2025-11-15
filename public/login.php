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

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unity Login</title>
    <link rel="stylesheet" href="assets/style.css">
    <!-- <link rel="stylesheet" href="admin/assets/css/admin.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="assets/logo.png" alt="Unity logo">
        </div>

        <div class="form-section">
            <div class="card">
                <h2>Login</h2>
                <form id="loginForm" action="../request/auth.php?login=1" method="post">
                    <div class="input-group">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" id="username" name="username" placeholder="Username">
                    </div>
                    <!--Password-->
                    <div class="input-group">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Password">
                    </div>
                    <!--Forgot Password-->
                    <div class="forgot">
                        <a href="#">Forgot Password?</a>
                    </div>
                    <!--Sign IN button-->
                    <button type="submit" class="btn">Sign In</button>
                    <!--SignUp link-->
                    <div class="signup">
                        New User? <a href="register.php">Sign up here</a>
                    </div>
                </form>
                <div>
                    <p>Testing account: Username = test11 || pass: 123</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <!-- <script src="js/main.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        if (performance.navigation.type === 1) {
            window.location.href = "login.php";
        }     
    </script>

    <!--     <script>
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
    </script> -->

    <?php 
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['error'])){

            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Login Error',
                    text: `" . htmlspecialchars($_GET['error']) . "`, 
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.history.pushState(null, '', 'login.php');
                    }
                });
            </script>";

        };
    ?>

    
</body>
</html>