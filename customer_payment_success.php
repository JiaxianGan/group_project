<?php
session_start();
include 'db_connect.php';

if (!isset($_POST['order_id'])) {
    header("Location: auth.php");
    exit();
}

$order_id = intval($_POST['order_id']);

// Update order status to 'Paid'
$stmt = $conn->prepare("UPDATE orders SET status = 'Paid' WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Success | AgriMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5 text-center">
    <h2 class="text-success mb-4">Payment Successful!</h2>
    <p>Thank you for your purchase. Your Order ID is <strong>#<?= htmlspecialchars($order_id); ?></strong>.</p>
    <p>Your order status has been updated to <strong>Paid</strong>.</p>
    <a href="order_history.php" class="btn btn-primary mt-3">View Order History</a>
    <a href="customer_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>
</body>
</html>
