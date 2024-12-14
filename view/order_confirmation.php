<?php
include '../config/connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

// Fetch order details
$sql = "SELECT o.order_id, o.total_price, o.order_date, o.status, 
               oi.dress_id, d.name AS dress_name, oi.quantity, oi.price_at_purchase
        FROM orders o
        JOIN order_items oi ON o.order_id = oi.order_id
        JOIN dresses d ON oi.dress_id = d.dress_id
        WHERE o.order_id = ? AND o.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

$order_items = [];
$order_details = null;

while ($row = $result->fetch_assoc()) {
    if ($order_details === null) {
        $order_details = [
            'order_id' => $row['order_id'],
            'total_price' => $row['total_price'],
            'order_date' => $row['order_date'],
            'status' => $row['status']
        ];
    }
    $order_items[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="../public/css/orderconfirmation.css">
</head>
<body>
    <h1>Order Confirmation</h1>
    <?php if ($order_details): ?>
        <p>Order #<?php echo $order_details['order_id']; ?></p>
        <p>Date: <?php echo $order_details['order_date']; ?></p>
        <p>Status: <?php echo $order_details['status']; ?></p>
        
        <h2>Order Items</h2>
        <table>
            <thead>
                <tr>
                    <th>Dress</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['dress_name']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($item['price_at_purchase'] * $item['quantity'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <p>Total: $<?php echo number_format($order_details['total_price'], 2); ?></p>
    <?php else: ?>
        <p>Invalid order.</p>
    <?php endif; ?>
</body>
</html>