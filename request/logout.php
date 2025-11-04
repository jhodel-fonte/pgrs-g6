<?php

if (isset($_GET['logout'])){
    session_start();
    session_destroy();
    session_unset();
    echo "<script>alert('Logging Out');</script>";
    header("Location: ../public/login.php");
    exit;
}


?>

