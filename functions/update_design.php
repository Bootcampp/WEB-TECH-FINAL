<?php
include '../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Start error logging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $dressId = $_POST['dress_id'];
    $name = $_POST['name'];
    $styleName = $_POST['style_name'];
    $price = $_POST['price'];

    // First, handle the style
    // Check if the style exists, if not, insert it
    $styleId = null;
    $styleStmt = $conn->prepare("SELECT style_id FROM dress_styles WHERE style_name = ?");
    $styleStmt->bind_param("s", $styleName);
    $styleStmt->execute();
    $styleResult = $styleStmt->get_result();

    if ($styleResult->num_rows == 0) {
        // Style doesn't exist, insert it
        $insertStyleStmt = $conn->prepare("INSERT INTO dress_styles (style_name) VALUES (?)");
        $insertStyleStmt->bind_param("s", $styleName);
        $insertStyleStmt->execute();
        $styleId = $conn->insert_id;
    } else {
        // Style exists, get its ID
        $styleRow = $styleResult->fetch_assoc();
        $styleId = $styleRow['style_id'];
    }

    // Define the upload directory
    $uploadDir = '../Uploads/'; // Updated path to Uploads folder

    // Ensure the upload directory exists
    if (!file_exists($uploadDir)) {
        // Create the directory with full permissions
        if (!mkdir($uploadDir, 0777, true)) {
            die("Failed to create upload directory");
        }
    }

    // Check if a new image file is uploaded
    $imageUrl = null;
    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] == 0) {
        // Generate a unique filename to prevent overwriting
        $fileInfo = pathinfo($_FILES['image_url']['name']);
        $fileName = uniqid() . '.' . $fileInfo['extension'];
        $targetFile = $uploadDir . $fileName;

        // Attempt to move the uploaded file
        if (move_uploaded_file($_FILES['image_url']['tmp_name'], $targetFile)) {
            // Convert to relative path for database storage
            $imageUrl = '../uploads/' . $fileName;
        } else {
            // Log the specific error
            error_log("File upload failed. Temp file: " . $_FILES['image_url']['tmp_name'] . ", Target: " . $targetFile);
            echo "Error uploading the image. Please check server permissions.";
            exit;
        }
    } else {
        // If no new image is uploaded, retain the existing image URL
        $imageUrl = $_POST['existing_image_url'] ?? null;
    }

    // Prepare the SQL query to update the design record
    $sql = "UPDATE dresses SET name = ?, style_id = ?, price = ?, image_url = ? WHERE dress_id = ?";
    
    // Prepare statement with error checking
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        // Log detailed prepare error
        error_log("Prepare failed: " . $conn->error);
        echo "Database prepare error: " . $conn->error;
        exit;
    }

    // Bind the parameters to the query
    $bindResult = $stmt->bind_param("sissi", $name, $styleId, $price, $imageUrl, $dressId);
    if ($bindResult === false) {
        // Log bind parameter error
        error_log("Bind parameters failed: " . $stmt->error);
        echo "Error binding parameters: " . $stmt->error;
        exit;
    }

    // Execute the query with error checking
    if ($stmt->execute()) {
        // Set a success message in the session
        session_start();
        $_SESSION['update_success'] = "Design updated successfully!";
        
        // Redirect to the design list page after successful update
        header("Location: ../view/my_designs.php");
        exit;
    } else {
        // Log the execution error
        error_log("Execute failed: " . $stmt->error);
        echo "Error updating design: " . $stmt->error;
        exit;
    }
}
?>