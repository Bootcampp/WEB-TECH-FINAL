<?php
function checkUserAccess($requiredRoleId) {
    // Start session if not already started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        // Redirect to login if not logged in
        header("Location: login.php");
        exit();
    }

    // Check if user has the required role
    if ($_SESSION['role_id'] != $requiredRoleId) {
        // Redirect to login if role doesn't match
        header("Location: login.php");
        exit();
    }

    // If all checks pass, return true
    return true;
}

?>