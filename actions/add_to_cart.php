<?php
include '../config/connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$dress_id = $_POST['dress_id'];
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

// Check if the dress is already in the cart
$sql = "SELECT * FROM cart WHERE user_id = ? AND dress_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $dress_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update quantity if already in the cart
    $sql = "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND dress_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $quantity, $user_id, $dress_id);
} else {
    // Insert new item into the cart
    $sql = "INSERT INTO cart (user_id, dress_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $user_id, $dress_id, $quantity);
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Item added to cart']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add item to cart']);
}

$stmt->close();
$conn->close();
?>
