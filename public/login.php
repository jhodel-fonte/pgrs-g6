<?php
session_start();
session_unset();

//just checking the session data

/* foreach ($_SESSION as $key => $val) {
    // make the value readable
    if (is_array($val) || is_object($val)) {
        $readable = print_r($val, true);
    } else {
        $readable = (string) $val;
    }

    // escape for HTML and convert newlines to <br>
    $safe = nl2br(htmlspecialchars($readable, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));

    // output with a label and a separator for clarity
    echo "<strong>" . htmlspecialchars($key, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . ":</strong> " . $safe . "<br>\n";
} */


if (isset($_SESSION['user']) && isset($_SESSION['isValid']) && $_SESSION['isValid'] == 1 ) {
    //aready login so it will redirect to main
    header("Location: ../.php");
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

        if (isset($_GET['error'])) {//this is just showing some error and will fix by frontend
            echo "
                <div>
                    <p>Error : " .htmlspecialchars($_GET['error']) ."</p>
                </div>
            ";
        }

        ?>

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
    
</body>
</html>