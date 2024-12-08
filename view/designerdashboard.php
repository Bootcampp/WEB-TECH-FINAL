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
                <li><a href="#">Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="dashboard-header">
                <h1>Welcome, Designer!</h1>
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
                            include '../config/connection.php';
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
</html>
