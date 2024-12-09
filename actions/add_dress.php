<?php
include '../config/connection.php'; // Include the database connection
include '../config/core.php';  

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['name'];
    $style = $_POST['style'];
    $newStyle = $_POST['new_style']; // For the new style input
    $price = $_POST['price'];

    // Debugging: Check if form data is received properly
    echo "Received form data: name=$name, style=$style, newStyle=$newStyle, price=$price<br>";

    // Determine if the designer selected an existing style or entered a new one
    if (!empty($newStyle)) {
        // Add the new style to the database
        $style = $newStyle; // Use the new style
        echo "New style entered: $style<br>"; // Debugging message for new style
    }

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Get file details
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $imageSize = $_FILES['image']['size'];
        $imageType = $_FILES['image']['type'];

        // Debugging: Check image file details
        echo "Image details: name=$imageName, size=$imageSize, type=$imageType<br>";

        // Define the allowed file types (e.g., jpg, png)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        // Check if the file type is allowed
        if (in_array($imageType, $allowedTypes)) {
            // Set the upload directory
            $uploadDir = '../uploads/';
            $imagePath = $uploadDir . $imageName;

            // Debugging: Check upload directory
            echo "Uploading image to directory: $uploadDir<br>";

            // Move the uploaded image to the designated folder
            if (move_uploaded_file($imageTmp, $imagePath)) {
                echo "Image uploaded successfully!<br>";

                // Check if the style already exists in the 'dress_styles' table
                $styleSql = "SELECT style_id FROM dress_styles WHERE style_name = ?";
                $stmt = $conn->prepare($styleSql);
                $stmt->bind_param("s", $style);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result && $result->num_rows > 0) {
                    // Style exists, get the style_id
                    $styleData = $result->fetch_assoc();
                    $styleId = $styleData['style_id'];
                    echo "Style exists, style_id=$styleId<br>"; // Debugging: Style found
                } else {
                    // Insert the new style into dress_styles table
                    $insertStyleSql = "INSERT INTO dress_styles (style_name) VALUES (?)";
                    $stmt = $conn->prepare($insertStyleSql);
                    $stmt->bind_param("s", $style);
                    $stmt->execute();
                    
                    // Get the new style_id
                    $styleId = $stmt->insert_id;
                    echo "New style inserted, style_id=$styleId<br>"; // Debugging: New style inserted
                }

                // Fetch the designer_id for the current user (assuming user_id is stored in the session)
                $designerSql = "SELECT designer_id FROM designers WHERE user_id = ?";
                $stmt = $conn->prepare($designerSql);
                $stmt->bind_param("i", $_SESSION['user_id']); // Use the user_id from the session
                $stmt->execute();
                $designerResult = $stmt->get_result();

                if ($designerResult && $designerResult->num_rows > 0) {
                    // Fetch the designer_id
                    $designerData = $designerResult->fetch_assoc();
                    $designerId = $designerData['designer_id'];
                    echo "Designer ID found: $designerId<br>"; // Debugging: Designer ID found
                } else {
                    // Handle the case where the designer entry is not found
                    echo "Designer ID not found for user_id: " . $_SESSION['user_id'] . "<br>"; // Debugging
                    header("Location: ../view/designerdashboard.php?msg=Designer%20not%20found.");
                    exit();
                }

                // Insert the dress into the dresses table
                $insertDressSql = "INSERT INTO dresses (designer_id, style_id, name, price, image_url, is_available) 
                                   VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insertDressSql);
                $isAvailable = 1; // Assuming the dress is available by default
                $stmt->bind_param("iisssi", $designerId, $styleId, $name, $price, $imagePath, $isAvailable);
                
                // Debugging: Check the query before executing
                echo "Executing query: $insertDressSql<br>";
                
                // Execute the insertion query
                $stmt->execute();

                // Check if the dress was inserted successfully
                if ($stmt->affected_rows > 0) {
                    // Redirect with success message
                    echo "Dress uploaded successfully!<br>"; // Debugging: Success message
                    header("Location: ../view/designerdashboard.php?msg=Design%20uploaded%20successfully.");
                    exit();
                } else {
                    // Redirect with error message
                    echo "Error uploading the design: " . $stmt->error . "<br>"; // Debugging: Error message
                    header("Location: ../view/designerdashboard.php?msg=Error%20uploading%20the%20design.");
                    exit();
                }

            } else {
                // Redirect with error message for image upload
                echo "Error uploading the image: " . $_FILES['image']['error'] . "<br>"; // Debugging: Upload error
                header("Location: ../view/designerdashboard.php?msg=Error%20uploading%20the%20image.");
                exit();
            }
        } else {
            // Redirect with error message for invalid file type
            echo "Invalid image file type: $imageType<br>"; // Debugging: Invalid file type
            header("Location: ../view/designerdashboard.php?msg=Invalid%20image%20file%20type.");
            exit();
        }
    } else {
        // Redirect with error message if no image is uploaded
        echo "No image uploaded. Error code: " . $_FILES['image']['error'] . "<br>"; // Debugging: No image uploaded
        header("Location: ../view/designerdashboard.php?msg=Please%20upload%20an%20image.");
        exit();
    }
} else {
    // If the request method isn't POST, redirect back with an error
    echo "Invalid request method<br>"; // Debugging: Invalid request method
    header("Location: ../view/designerdashboard.php?msg=Invalid%20request.");
    exit();
}
?>
