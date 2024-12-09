<?php
// Include the necessary files for the database connection
include '../config/connection.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to delete a design.";
    exit();
}

// Check if a dress_id is provided
if (!isset($_GET['dress_id'])) {
    echo "No design ID provided.";
    exit();
}

// Get the provided dress_id and the logged-in user's user_id
$dressId = intval($_GET['dress_id']);
$userId = $_SESSION['user_id'];

// Fetch designer_id based on the logged-in user
$queryDesigner = "SELECT designer_id FROM designers WHERE user_id = ?";
$stmtDesigner = $conn->prepare($queryDesigner);

if (!$stmtDesigner) {
    die("Query preparation failed: " . $conn->error);
}

$stmtDesigner->bind_param("i", $userId);
$stmtDesigner->execute();
$resultDesigner = $stmtDesigner->get_result();

if ($resultDesigner->num_rows > 0) {
    $designerData = $resultDesigner->fetch_assoc();
    $designerId = $designerData['designer_id'];

    // Verify ownership of the dress
    $queryVerify = "SELECT * FROM dresses WHERE dress_id = ? AND designer_id = ?";
    $stmtVerify = $conn->prepare($queryVerify);

    if (!$stmtVerify) {
        die("Query preparation failed: " . $conn->error);
    }

    $stmtVerify->bind_param("ii", $dressId, $designerId);
    $stmtVerify->execute();
    $resultVerify = $stmtVerify->get_result();

    if ($resultVerify->num_rows > 0) {
        // If ownership is verified, delete the dress
        $queryDelete = "DELETE FROM dresses WHERE dress_id = ?";
        $stmtDelete = $conn->prepare($queryDelete);

        if (!$stmtDelete) {
            die("Query preparation failed: " . $conn->error);
        }

        $stmtDelete->bind_param("i", $dressId);

        if ($stmtDelete->execute()) {
            header("Location: ../view/my_designs.php?message=Design deleted successfully");
            exit();
        } else {
            echo "Error deleting design: " . $stmtDelete->error;
        }
    } else {
        echo "Ownership verification failed or design not found.";
    }
} else {
    echo "Designer not found for the logged-in user.";
}
exit();
?>
