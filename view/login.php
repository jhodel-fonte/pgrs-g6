<?php
session_start();

if (isset($_SESSION['user']) && isset($_SESSION['isValid']) && $_SESSION['isValid'] == 1 ) {
    //aready login so it will redirect to main
    header("Location: ../view/landing.php");
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unity Login</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="assets/logo.png" alt="Unity logo">
        </div>

    <?php

    if (isset($_GET['error'])) {
        echo "
            <div>
                <p>Error : " .htmlspecialchars($_GET['error']) ."</p>
            </div>
        ";
    }

    ?>

        <!--Email-->
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
                        New User? <a href="register.html">Sign up here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <!-- <script src="js/main.js"></script> -->

    <script>
        if (performance.navigation.type === 1) {
            window.location.href = "login.php";
        }     
    </script>
    
</body>
</html>