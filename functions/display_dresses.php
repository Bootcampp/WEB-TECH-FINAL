<?php
// Include the necessary files for the database connection and session
include '../config/connection.php';
include '../config/core.php';

// Function to fetch the designer ID based on the logged-in user's ID
function getDesignerId($userId) {
    global $conn;
    
    // Query to get the designer ID from the users table (assuming 'designer_id' is stored in the users table)
    $sql = "SELECT designer_id FROM designers WHERE user_id = ?";
    
    // Prepare the query and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    
    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if we found a designer for the user
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['designer_id'];
    } else {
        return null;  // No designer found for the user
    }
}

// Function to fetch dresses for the designer
function fetchDesigns($designerId) {
    global $conn;

    // Query to get the dresses for the designer
    $sql = "SELECT dresses.dress_id, dresses.name, dresses.price, dresses.image_url, dress_styles.style_name
            FROM dresses
            JOIN dress_styles ON dresses.style_id = dress_styles.style_id
            WHERE dresses.designer_id = ?";

    // Prepare the query and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $designerId);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the designer has any dresses
    if ($result->num_rows > 0) {
        $designs = [];
        while ($row = $result->fetch_assoc()) {
            $designs[] = $row;
        }
        return $designs;
    } else {
        return [];
    }
}
?>
