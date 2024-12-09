<?php
include '../config/connection.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data and sanitize it
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; // Password entered by the user
    $role_id = $_POST['role_id']; // Get role ID (user or designer)

    // Validate if email already exists
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Email already exists
        header("Location: ../view/signup.php?msg=Email%20already%20exists.");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into the database with is_active set to 1
    $sql = "INSERT INTO users (full_name, email, password, role_id, is_active) 
            VALUES ('$full_name', '$email', '$hashed_password', '$role_id', 1)";
    
    if (mysqli_query($conn, $sql)) {
        // Get the newly created user's ID
        $user_id = mysqli_insert_id($conn);

        // If the user is a designer, add an entry to the designers table
        if ($role_id == 2) { // Assuming '2' is the role ID for designers
            $designer_sql = "INSERT INTO designers (user_id) VALUES ('$user_id')";
            if (!mysqli_query($conn, $designer_sql)) {
                // Handle error if the designer entry fails
                header("Location: ../view/signup.php?msg=Error%20registering%20designer.");
                exit();
            }
        }

        // If successful, redirect to login page
        header("Location: ../view/login.php?msg=Registration%20successful.%20Please%20log%20in.");
        exit();
    } else {
        // If there was an error during the insert
        header("Location: ../view/signup.php?msg=Error%20registering%20user.");
        exit();
    }
} else {
    // If the request method isn't POST, redirect back to signup page with an error
    header("Location: ../view/signup.php?msg=Invalid%20request.");
    exit();
}
?>
