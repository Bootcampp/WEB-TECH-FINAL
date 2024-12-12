<?php
include '../config/connection.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data and sanitize it
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; // Password entered by the user

    // Validate the user's credentials
    $sql = "SELECT * FROM users WHERE email = '$email' AND is_active = 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start the session
            session_start();
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role_id'] = $user['role_id'];

            // Redirect based on role_id (use numbers instead of role names)
            $role_id = (int) $user['role_id'];
            
            // Check role_id and redirect accordingly
            if ($role_id === 1) { // Role ID 1 is 'user'
                header("Location: ../view/usersdashboard.php");
                exit();
            } elseif ($role_id === 2) { // Role ID 2 is 'designer'
                header("Location: ../view/designerdashboard.php");
                exit();
            } else {
                // If role_id is not recognized, redirect to a generic dashboard or error page
                header("Location: ../view/user_dashboard.php");
                exit();
            }
        } else {
            // Incorrect password
            header("Location: ../view/login.php?msg=Invalid%20email%20or%20password.");
            exit();
        }
    } else {
        // User not found or inactive
        header("Location: ../view/login.php?msg=No%20user%20found%20with%20this%20email.");
        exit();
    }
} else {
    // If the request method isn't POST, redirect back to login page with an error
    header("Location: ../view/login.php?msg=Invalid%20request.");
    exit();
}
?>
