<?php
// Start the session
session_start();

// Destroy all session variables to log the user out
session_unset();

// Destroy the session
session_destroy();

// Redirect the user to the login page or another page
header("Location: ../view/login.php?msg=You have been logged out successfully.");
exit;
?>