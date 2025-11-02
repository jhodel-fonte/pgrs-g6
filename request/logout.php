<?php

if (isset($_GET['logout'])){
    session_destroy();
    echo "<script>alert('Logging Out');</script>";
    header("Location: ../public/login.php");
    exit;
}


?>

