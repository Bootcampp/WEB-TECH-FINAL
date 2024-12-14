<?php
// Include necessary files for database connection
include '../config/connection.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION ['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

require_once '../functions/page_direct.php';
checkUserAccess(1);

// Initialize search query
$searchQuery = '';
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
}

// Fetch all designs with designer names and styles, filter by search query
$sql = "SELECT 
            d.dress_id, 
            d.name AS dress_name, 
            d.price, 
            d.image_url, 
            ds.style_name, 
            u.full_name AS designer_name 
        FROM dresses d
        INNER JOIN dress_styles ds ON d.style_id = ds.style_id
        INNER JOIN designers de ON d.designer_id = de.designer_id
        INNER JOIN users u ON de.user_id = u.user_id
        WHERE d.is_available = 1 
        AND u.full_name LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$searchQuery%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are designs to display
$designs = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $designs[] = $row;
    }
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Dashboard</title>
    <link rel="stylesheet" href="../public/css/userdashboard.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-container">
        <a href="cart.php">Cart</a>
            <a href="../actions/logout.php" class="logout-btn">Logout</a>

            <form method="GET" action="" class="search-form">
                <input 
                    type="text" 
                    name="search" 
                    class="search-input" 
                    placeholder="Search for designers..." 
                    value="<?php echo htmlspecialchars($searchQuery); ?>"
                >
                <button type="submit" class="search-btn">Search</button>
            </form>
        </div>
    </nav>

    <div class="dashboard-container">
        <!-- Header -->
        <header class="dashboard-header">
            <h1>Explore Bridal Designs</h1>
            <p>Discover stunning dresses from top Ghanaian designers.</p>
        </header>

        <!-- Designs Gallery -->
        <div class="designs-gallery">
            <?php if (!empty($designs)): ?>
                <?php foreach ($designs as $design): ?>
                    <div class="design-card">
                        <img src="<?php echo htmlspecialchars($design['image_url']); ?>" alt="<?php echo htmlspecialchars($design['dress_name']); ?>" class="design-image">
                        <div class="design-info">
                            <h3><?php echo htmlspecialchars($design['dress_name']); ?></h3>
                            <p class="designer-name">Designer: <?php echo htmlspecialchars($design['designer_name']); ?></p>
                            <p class="style-name">Style: <?php echo htmlspecialchars($design['style_name']); ?></p>
                            <p class="price">Price: $<?php echo htmlspecialchars($design['price']); ?></p>
                            <div class="design-actions">
                                <!-- Added data-dress-id attribute to each Add to Cart button -->
                                <button class="btn save-btn" data-dress-id="<?php echo htmlspecialchars($design['dress_id']); ?>">Add to cart</button>
                                <button class="btn purchase-btn">Purchase</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No designs available at the moment. Please check back later!</p>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Dress Details Modal -->
    <div id="dress-details-modal" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <div class="modal-image-container">
                <img id="modal-dress-image" src="" alt="Dress Image" class="modal-dress-image">
            </div>
            <div class="modal-details">
                <h2 id="modal-dress-name"></h2>
                <p id="modal-designer-name" class="modal-designer"></p>
                <p id="modal-style-name" class="modal-style"></p>
                <p id="modal-price" class="modal-price"></p>
                <div class="modal-actions">
                    <button class="btn save-btn">Add to cart</button>
                    <button class="btn purchase-btn">Purchase</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script src="../public/js/userdasboard.js"></script>
</body>
</html>
