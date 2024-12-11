<?php
// Include the necessary files for the database connection and session
include '../config/connection.php';
session_start();

// Function to fetch designer's full name
function getDesignerFullName($conn) {
    // Check if the user is logged in
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];

        // Fetch the designer's full name from the database using the user_id
        $sql = "SELECT full_name FROM users WHERE user_id = '$userId'";
        $result = mysqli_query($conn, $sql);
        if ($row = mysqli_fetch_assoc($result)) {
            return $row['full_name']; // Return the full name
        } else {
            // Return a default value if no name is found
            return "Designer";
        }
    } else {
        // If the user is not logged in, return a default value
        return "Designer";
    }
}
?>
