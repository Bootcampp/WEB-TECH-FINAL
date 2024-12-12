<?php
// Include necessary files for database connection and session management
include '../config/connection.php';
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Fetch the designer's full name by joining users and designers tables
    $sql = "SELECT u.full_name 
            FROM users u
            INNER JOIN designers d ON u.user_id = d.user_id
            WHERE u.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the designer exists, fetch the full name
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $designerName = $row['full_name'];
    } else {
        $designerName = "Designer"; // Default fallback if no name is found
    }
} else {
    // Redirect to login if the user is not logged in
    header("Location: ../view/login.php");
    exit();
}

require_once '../functions/page_direct.php';
checkUserAccess(2);
// Rest of designer dashboard code
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Designer Dashboard</title>
    <link rel="stylesheet" href="../public/css/designerdashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>BridalConnect</h2>
            <ul>
                <li><a href="#">Dashboard</a></li>
                <li><a href="my_designs.php">My Designs</a></li>
                <li><a href="#">Upload New Design</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="../actions/logout.php">Logout</a></li>

            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="dashboard-header">
                <h1>Welcome, <?php echo htmlspecialchars($designerName); ?>!</h1>
            </header>

            <!-- Form for Uploading Designs -->
            <section class="upload-form">
                <h2>Upload a New Design</h2>
                <form action="../actions/add_dress.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Dress Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter dress name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="style">Style</label>
                        <select id="style" name="style">
                            <option value="">Select a style</option>
                            <?php
                            // Fetch styles from the dress_styles table
                            $sql = "SELECT style_id, style_name FROM dress_styles";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['style_name'] . "'>" . $row['style_name'] . "</option>";
                            }
                            ?>
                        </select>
                        <label for="new_style">Or add a new style:</label>
                        <input type="text" id="new_style" name="new_style" placeholder="Enter new style (if applicable)">
                    </div>

                    <div class="form-group">
                        <label for="price">Price (GHS)</label>
                        <input type="number" id="price" name="price" placeholder="Enter price" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="image">Upload Image</label>
                        <input type="file" id="image" name="image" accept="image/*" required>
                    </div>

                    <button type="submit" class="btn">Upload Design</button>
                </form>
            </section>
        </main>
    </div>
</body>
<script src="../public/js/designerdashboard.js"></script>
</html>
