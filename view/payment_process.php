<?php
include '../config/connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Validate POST data
if (!isset($_POST['cart_ids'], $_POST['dress_ids'], $_POST['quantities'], $_POST['total_price'])) {
    $_SESSION['error_message'] = "Invalid checkout data.";
    header("Location: cart.php");
    exit();
}

$cart_ids = $_POST['cart_ids'];
$dress_ids = $_POST['dress_ids'];
$quantities = $_POST['quantities'];
$total_price = $_POST['total_price'];

// Start a transaction
$conn->begin_transaction();

try {
    // Create an order
    $order_sql = "INSERT INTO orders (user_id, total_price) VALUES (?, ?)";
    $order_stmt = $conn->prepare($order_sql);
    $order_stmt->bind_param("id", $user_id, $total_price);
    $order_stmt->execute();
    $order_id = $conn->insert_id;
    $order_stmt->close();

    // Insert order items
    $order_item_sql = "INSERT INTO order_items (order_id, dress_id, quantity, price_at_purchase) VALUES (?, ?, ?, (SELECT price FROM dresses WHERE dress_id = ?))";
    $order_item_stmt = $conn->prepare($order_item_sql);

    foreach ($dress_ids as $index => $dress_id) {
        $quantity = $quantities[$index];
        $order_item_stmt->bind_param("iiii", $order_id, $dress_id, $quantity, $dress_id);
        $order_item_stmt->execute();
    }
    $order_item_stmt->close();

    // Remove items from cart
    $delete_cart_sql = "DELETE FROM cart WHERE cart_id IN (" . implode(',', array_fill(0, count($cart_ids), '?')) . ")";
    $delete_cart_stmt = $conn->prepare($delete_cart_sql);
    $types = str_repeat('i', count($cart_ids));
    $delete_cart_stmt->bind_param($types, ...$cart_ids);
    $delete_cart_stmt->execute();
    $delete_cart_stmt->close();

    // Commit transaction
    $conn->commit();

    // Redirect to order confirmation
    $_SESSION['success_message'] = "Order placed successfully!";
    header("Location: order_confirmation.php?order_id=" . $order_id);
    exit();

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    $_SESSION['error_message'] = "Error processing order: " . $e->getMessage();
    header("Location: cart.php");
    exit();
}