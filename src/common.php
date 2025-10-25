<?php
//this file to help some functions like input sanitation
//this is a utility function

function sanitizeInput($input) {//sanitize and remove whitespace
    return htmlspecialchars(trim($input));
}

?>