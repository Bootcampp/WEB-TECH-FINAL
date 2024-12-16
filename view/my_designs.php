<?php
// Include the necessary files for the database connection and session
include '../config/connection.php';
include '../functions/display_dresses.php';

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Get the designer ID for the logged-in user
    $designerId = getDesignerId($userId);

    // If the designer ID exists, fetch the dresses
    if ($designerId) {
        $designs = fetchDesigns($designerId);
    } else {
        // If no designer is found for the user, redirect or show an error
        header("Location: ../view/login.php");
        exit();
    }
} else {
    // If the user is not logged in, redirect to login
    header("Location: ../view/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Designs</title>
    <link rel="stylesheet" href="../public/css/my_designs.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>BridalConnect</h2>
            <ul>
                <li><a href="designerdashboard.php">Upload New Design</a></li>
                <li><a href="designer_analytics.php">Analytics</a></li>
                <li><a href="../actions/logout.php">Logout</a></li>

            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="dashboard-header">
                <h1>My Designs</h1>
                <p>Manage your designs below. Edit or delete designs as needed.</p>
            </header>

            <!-- Designs Grid -->
            <section class="designs-grid">
                <?php if (count($designs) > 0): ?>
                    <?php foreach ($designs as $design): ?>
                        <div class="design-card" data-design='<?php echo json_encode($design); ?>'>
                            <img src="<?php echo $design['image_url']; ?>" alt="Dress Image">
                            <div class="design-details">
                                <h3><?php echo $design['name']; ?></h3>
                                <p>Style: <?php echo $design['style_name']; ?></p>
                                <p>Price: GHS <?php echo number_format($design['price'], 2); ?></p>
                                <div class="design-actions">
                                    <button class="btn edit-btn" data-dress-id="<?php echo $design['dress_id']; ?>">Edit</button>
                                    <a href="../functions/delete_design.php?dress_id=<?php echo $design['dress_id']; ?>" class="btn delete-btn">Delete</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No designs found.</p>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <!-- Modal for Editing Design -->
    <div id="editModal" class="modal-container">
    <div class="modal-content">
    <form id="editModal" method="POST" action="../functions/update_design.php" enctype="multipart/form-data">

    <h2>Edit Design</h2>
    <input type="hidden" name="dress_id" id="dressId">
    <div>
        <label for="name">Name</label>
        <input type="text" name="name" id="designName" required>
    </div>
    <div>
        <label for="style_name">Style</label>
        <input type="text" name="style_name" id="designStyle" required>
    </div>
    <div>
        <label for="price">Price (GHS)</label>
        <input type="number" step="0.01" name="price" id="designPrice" required>
    </div>
    <div>
    <label for="image_url">Image</label>
    <input type="file" name="image_url" id="designImage" accept="image/*">
    <img id="imagePreview" src="" alt="Image preview" style="max-width: 200px; display: none;">
    
</div>
<!-- Add this to your edit modal form -->
<input type="hidden" name="existing_image_url" id="existingImageUrl" value="">
    <div class="modal-actions">
        <button type="submit" class="btn save-btn">Save</button>
        <button type="button" class="btn cancel-btn" id="cancelModal">Cancel</button>

</form>
</div>
</div>
    <style>
/* Modal Background */
.modal-container {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 500px;
}


/* Close Button */
.close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: transparent;
    border: none;
    font-size: 20px;
    cursor: pointer;
}

/* Modal Header */
.modal-header {
    font-size: 1.5em;
    font-weight: bold;
    margin-bottom: 10px;
}

/* Modal Body */
.modal-body {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

/* Input Fields */
input[type="text"], input[type="number"], input[type="file"] {
    padding: 10px;
    margin: 5px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
}

/* Image Preview */
#imagePreview {
    max-width: 100%;
    max-height: 200px;
    margin-top: 10px;
}

/* Submit and Cancel Buttons */
button {
    padding: 10px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}

/* Cancel Button */
#cancelModal {
    background-color: #f44336;
}

#cancelModal:hover {
    background-color: #d32f2f;
}

    </style>

    <script src="../public/js/my_designs.js"></script>
</body>
</html>
