<?php
// actions/purchase.php
session_start();
include '../config/connection.php';
require_once '../functions/page_direct.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in to purchase.']);
    exit();
}

// Check user access
checkUserAccess(1);

// Validate purchase request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $dress_id = isset($_POST['dress_id']) ? intval($_POST['dress_id']) : 0;
    $user_id = $_SESSION['user_id'];

    if ($dress_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid dress selection.']);
        exit();
    }

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Check dress availability and get details
        $check_sql = "SELECT d.dress_id, d.price, d.name AS dress_name, d.is_available, 
                             d.designer_id, ds.style_name 
                      FROM dresses d
                      JOIN dress_styles ds ON d.style_id = ds.style_id
                      WHERE d.dress_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $dress_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception('Dress not found.');
        }

        $dress = $result->fetch_assoc();
        
        if ($dress['is_available'] == 0) {
            throw new Exception('This dress is no longer available.');
        }

        // Create order
        $order_sql = "INSERT INTO orders (user_id, total_price) VALUES (?, ?)";
        $order_stmt = $conn->prepare($order_sql);
        $order_stmt->bind_param("id", $user_id, $dress['price']);
        $order_stmt->execute();
        $order_id = $conn->insert_id;

        // Create order item
        $order_item_sql = "INSERT INTO order_items (order_id, dress_id, quantity, price_at_purchase) 
                           VALUES (?, ?, 1, ?)";
        $order_item_stmt = $conn->prepare($order_item_sql);
        $order_item_stmt->bind_param("iid", $order_id, $dress_id, $dress['price']);
        $order_item_stmt->execute();

        // Mark dress as unavailable
        $update_sql = "UPDATE dresses SET is_available = 0 WHERE dress_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $dress_id);
        $update_stmt->execute();

        // Commit transaction
        $conn->commit();

        echo json_encode([
            'success' => true, 
            'message' => 'Purchase completed successfully!',
            'dress_name' => $dress['dress_name'],
            'style_name' => $dress['style_name'],
            'price' => $dress['price']
        ]);
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();

        echo json_encode([
            'success' => false, 
            'message' => $e->getMessage()
        ]);
    }

    exit();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit();
}