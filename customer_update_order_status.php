<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: auth.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Update the order status in the database
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ? AND customer_id = ?");
    $stmt->bind_param("sii", $status, $order_id, $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        header("Location: customer_order_track.php"); // Redirect to order tracking page
    } else {
        echo "Error updating status!";
    }
}
?>
