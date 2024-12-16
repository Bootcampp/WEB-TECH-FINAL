<?php
include '../config/connection.php';
session_start();

// Check if user is logged in and is a designer
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'designer') {
//     header('Location: login.php');
//     exit();
// }

try {
    // Get designer ID from designers table
    $stmt = $conn->prepare("SELECT designer_id FROM designers WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $designer = $result->fetch_assoc();

    if (!$designer) {
        throw new Exception("Designer profile not found");
    }

    // Prepare analytics query
    $stmt = $conn->prepare("
        SELECT 
            d.designer_id,
            u.full_name AS designer_name,
            COUNT(DISTINCT dresses.dress_id) AS total_designs,
            COALESCE(SUM(oi.quantity), 0) AS total_designs_purchased,
            COALESCE(SUM(oi.quantity * oi.price_at_purchase), 0) AS total_revenue,
            (
                SELECT COUNT(DISTINCT dress_id) 
                FROM dresses d2 
                WHERE d2.designer_id = d.designer_id AND d2.is_available = 1
            ) AS available_designs
        FROM 
            designers d
        JOIN 
            users u ON d.user_id = u.user_id
        LEFT JOIN 
            dresses ON d.designer_id = dresses.designer_id
        LEFT JOIN 
            order_items oi ON dresses.dress_id = oi.dress_id
        WHERE 
            d.designer_id = ?
        GROUP BY 
            d.designer_id, u.full_name
    ");
    $stmt->bind_param("i", $designer['designer_id']);
    $stmt->execute();
    $analytics = $stmt->get_result()->fetch_assoc();

    // Get recent sales
    $sales_stmt = $conn->prepare("
        SELECT 
            d.name AS dress_name, 
            oi.quantity, 
            oi.price_at_purchase,
            o.order_date
        FROM 
            dresses d
        JOIN 
            order_items oi ON d.dress_id = oi.dress_id
        JOIN 
            orders o ON oi.order_id = o.order_id
        WHERE 
            d.designer_id = ?
        ORDER BY 
            o.order_date DESC
        LIMIT 5
    ");
    $sales_stmt->bind_param("i", $designer['designer_id']);
    $sales_stmt->execute();
    $recent_sales = $sales_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

} catch (Exception $e) {
    // Log error and redirect
    error_log($e->getMessage());
    header('Location: error.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Designer Analytics Dashboard</title>
    <link rel="stylesheet" href="../public/css/designer_analytics.css">
</head>
<body>
    <div class="container">
        <h1>Designer Analytics Dashboard</h1>
        <li><a href="my_designs.php">My Designs</a></li>
        <div class="analytics-grid">
            <div class="card design-overview">
                <h2>Design Overview</h2>
                <div class="stat-item">
                    <span>Total Designs Uploaded:</span>
                    <span id="total-designs"><?php echo $analytics['total_designs']; ?></span>
                </div>
                <div class="stat-item">
                    <span>Currently Available Designs:</span>
                    <span id="available-designs"><?php echo $analytics['available_designs']; ?></span>
                </div>
            </div>

            <div class="card sales-performance">
                <h2>Sales Performance</h2>
                <div class="stat-item">
                    <span>Total Designs Sold:</span>
                    <span id="total-sold"><?php echo $analytics['total_designs_purchased']; ?></span>
                </div>
                <div class="stat-item">
                    <span>Total Revenue:</span>
                    <span id="total-revenue">$<?php echo number_format($analytics['total_revenue'], 2); ?></span>
                </div>
            </div>
        </div>

        <div class="card recent-sales">
            <h2>Recent Sales</h2>
            <table id="sales-table">
                <thead>
                    <tr>
                        <th>Dress Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recent_sales)): ?>
                        <tr>
                            <td colspan="4">No recent sales</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recent_sales as $sale): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($sale['dress_name']); ?></td>
                                <td><?php echo $sale['quantity']; ?></td>
                                <td>$<?php echo number_format($sale['price_at_purchase'], 2); ?></td>
                                <td><?php echo date('M d, Y', strtotime($sale['order_date'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="../public/js/designer_analytics.js"></script>
</body>
</html>