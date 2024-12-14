<?php
include 'db_connection.php';

if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);
    echo "<h1>Order #{$order_id} Confirmed!</h1>";
    echo "<p>Thank you for your purchase.</p>";
} else {
    echo "<p>No order to confirm.</p>";
}
?>
