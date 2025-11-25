<?php 
//this is for testing the session data only remove when wehn
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION)) {
    foreach ($_SESSION as $key => $value) {
        echo "<p>Variable: " . htmlspecialchars($key) . "</p>";

        if (is_array($value)) {
            echo "<p>Value: (Array)</p>\n";
            echo "<pre>" . htmlspecialchars(print_r($value, true)) . "</pre>\n";
        } else {
            echo "<p>Value: " . htmlspecialchars($value) . "</p>\n";
        }
    }
} else {
    echo "<p>No session variables are currently set.</p>\n";
}

?>