<?php
include '../config/connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items
$sql = "SELECT 
            c.cart_id,
            c.quantity, 
            d.name AS dress_name, 
            d.price, 
            d.image_url, 
            (c.quantity * d.price) AS total_price 
        FROM cart c
        INNER JOIN dresses d ON c.dress_id = d.dress_id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$total_price = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total_price += $row['total_price'];
    }
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="../public/css/cart.css">
</head>
<body>
    <h1>Your Cart</h1>
    <form action="process_checkout.php" method="POST">
        <table>
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Image</th>
                    <th>Dress</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><input type="checkbox" name="selected_items[]" value="<?php echo $item['cart_id']; ?>"></td>
                        <td>
                            <img 
                                src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                alt="Dress Image" 
                                class="cart-dress-image" 
                                style="width: 100px; height: auto;">
                        </td>
                        <td><?php echo htmlspecialchars($item['dress_name']); ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($item['total_price'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p>Total Price: $<?php echo number_format($total_price, 2); ?></p>
        <button type="submit">Proceed to Checkout</button>
    </form>
</body>
<script src="../public/js/cart.js"></script>
</html>
