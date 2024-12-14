<?php
include '../config/connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if items were selected
if (!isset($_POST['selected_items']) || empty($_POST['selected_items'])) {
    // Redirect back to cart with an error message
    $_SESSION['error_message'] = "Please select at least one item to checkout.";
    header("Location: cart.php");
    exit();
}

// Prepare to fetch selected items
$selected_cart_ids = $_POST['selected_items'];
$placeholders = implode(',', array_fill(0, count($selected_cart_ids), '?'));

$sql = "SELECT 
            c.cart_id,
            c.quantity, 
            d.dress_id,
            d.name AS dress_name, 
            d.price, 
            d.image_url, 
            (c.quantity * d.price) AS total_price 
        FROM cart c
        INNER JOIN dresses d ON c.dress_id = d.dress_id
        WHERE c.user_id = ? AND c.cart_id IN ($placeholders)";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

// Bind parameters dynamically
$types = 'i' . str_repeat('i', count($selected_cart_ids)); // 'i' for user_id, additional 'i's for cart IDs
$params = array_merge([$user_id], $selected_cart_ids);

// Use unpacking operator (...) to bind parameters by reference
$stmt->bind_param($types, ...$params);

$stmt->execute();
$result = $stmt->get_result();

$checkout_items = [];
$total_checkout_price = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $checkout_items[] = $row;
        $total_checkout_price += $row['total_price'];
    }
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="../public/css/checkout.css">
</head>
<body>
    <h1>Checkout</h1>
    
    <?php if (!empty($checkout_items)): ?>
        <form action="payment_process.php" method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Dress</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($checkout_items as $item): ?>
                        <tr>
                            <td>
                                <img 
                                    src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                    alt="Dress Image" 
                                    class="checkout-dress-image" 
                                    style="width: 100px; height: auto;">
                            </td>
                            <td><?php echo htmlspecialchars($item['dress_name']); ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>$<?php echo number_format($item['total_price'], 2); ?></td>
                            
                            <!-- Hidden inputs to pass item details to payment process -->
                            <input type="hidden" name="cart_ids[]" value="<?php echo $item['cart_id']; ?>">
                            <input type="hidden" name="dress_ids[]" value="<?php echo $item['dress_id']; ?>">
                            <input type="hidden" name="quantities[]" value="<?php echo $item['quantity']; ?>">
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <p>Total Checkout Price: $<?php echo number_format($total_checkout_price, 2); ?></p>
            <input type="hidden" name="total_price" value="<?php echo $total_checkout_price; ?>">
            
            <button type="submit">Proceed to Payment</button>
        </form>
    <?php else: ?>
        <p>No items selected for checkout.</p>
    <?php endif; ?>
</body>
</html>
