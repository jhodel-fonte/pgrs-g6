<?php
session_start();

if (isset($_SESSION['user']) && isset($_SESSION['isValid']) && $_SESSION['isValid'] == 1 ) {
  //hmmm i think this one set variables any ways it is on the session so it easy to recall
  $user = $_SESSION['user'];
  // var_dump($user);
} else {
  header("Location: error.html");
  exit;
}

require_once __DIR__ .'../../controller/logout.php';


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Unity Landing</title>
  <link rel="stylesheet" href="assets/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="landing">
  <div class="landing-container">
    <img src="assets/logo.png" alt="UNITY Logo">
    <h1>Welcome, <span id="username"><?= (isset($user['username'])) ? $user['username'] : "NO Name" ?></span> 🎉</h1>
    <p>You are now logged in to Unity System. <br> (sample landing page)</p>
    <p>PG-CODE : <span><?= (isset($user['pgCode'])) ? $user['pgCode'] : "NO Name"  ?></span></p>

  <form method="GET">
    <input type="hidden" name="logout" value="1">
    <button class="btn" id="logoutBtn"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
  </form>


    
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
  <script src="main.js"></script>
</body>
</html>
