<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}
include 'db_connect.php';
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $stmt = $conn->prepare("DELETE FROM order_tracking WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $reset_stmt = $conn->prepare("UPDATE orders SET status = 'Pending' WHERE order_id = ?");
    $reset_stmt->bind_param("i", $order_id);
    $reset_stmt->execute();
    header("Location: staff_delivery.php");
    exit();
}
?>