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
                <li><a href="designerdashboard.php">Dashboard</a></li>
                <!-- <li><a href="#">My Designs</a></li> -->
                <li><a href="upload_design.php">Upload New Design</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
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
                <!-- Example Design Card -->
                <div class="design-card" data-image="dress1.jpg">
                    <img src="../assets/mermaid dress.jpeg" alt="Dress Image">
                    <div class="design-details">
                        <h3>Elegant Gown</h3>
                        <p>Style: Mermaid</p>
                        <p>Price: GHS 800</p>
                        <div class="design-actions">
                            <a href="#" class="btn edit-btn">Edit</a>
                            <a href="#" class="btn delete-btn">Delete</a>
                        </div>
                    </div>
                </div>

                <div class="design-card" data-image="dress2.jpg">
                    <img src="../assets/ball gown.jpeg" alt="Dress Image">
                    <div class="design-details">
                        <h3>Princess Ball Gown</h3>
                        <p>Style: Ball Gown</p>
                        <p>Price: GHS 1200</p>
                        <div class="design-actions">
                            <a href="#" class="btn edit-btn">Edit</a>
                            <a href="#" class="btn delete-btn">Delete</a>
                        </div>
                    </div>
                </div>


                <div class="design-card" data-image="dress3.jpg">
                    <img src="../assets/strapless gown.jpeg" alt="Dress Image">
                    <div class="design-details">
                        <h3>Princess theme</h3>
                        <p>Style: Strapless</p>
                        <p>Price: GHS 2200</p>
                        <div class="design-actions">
                            <a href="#" class="btn edit-btn">Edit</a>
                            <a href="#" class="btn delete-btn">Delete</a>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Lightbox
    <div class="lightbox" id="lightbox">
        <div class="lightbox-content">
            <span class="close-btn">&times;</span>
            <img id="lightbox-img" src="../assets/Bridal.jpeg" alt="Expanded Design">
        </div>
    </div> -->

    <script src="../public/js/my_designs.js"></script>
</body>
</html>
