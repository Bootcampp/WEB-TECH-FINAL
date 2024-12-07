<?php

// Display all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection
include '../db/database.php';
include '../db/config.php';

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get email and password from the POST request
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Validate the inputs
    if (!empty($email) && !empty($password)) {
        // Prepare the SQL statement
        $stmt = mysqli_prepare($connection, "SELECT user_id, password, role FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email); // Bind the email parameter

        // Execute the prepared statement
        mysqli_stmt_execute($stmt);

        // Bind result variables
        mysqli_stmt_bind_result($stmt, $user_id, $hashed_password, $role);

        // Fetch the result
        if (mysqli_stmt_fetch($stmt)) {
            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Store user information in the session
                $_SESSION['user_id'] = $user_id;
                $_SESSION['fname'] = $fname;
                $_SESSION['lname'] = $lname;
                $_SESSION['role'] = $role;

                
                redirectBasedOnRole();

                // Redirect based on user role
                if ($role == 1) {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: ../view/userdashboard.php");
                }
                exit;
            } else {
                // Password is incorrect
                header("Location: ../view/login.php?msg=Invalid password.");
                exit;
            }
        } else {
            // Email not found
            header("Location: ../view/login.php?msg=User with this email does not exist.");
            exit;
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        header("Location: ../view/login.php?msg=Please enter both email and password.");
        exit;
    }
} else {
    header("Location: ../view/login.php?msg=Invalid access method.");
    exit;
}

// Close the database connection
mysqli_close($connection);
