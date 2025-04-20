<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Add delivery entry to order_tracking
    $stmt = $conn->prepare("INSERT INTO order_tracking (order_id, status) VALUES (?, ?)");
    $stmt->bind_param("is", $order_id, $status);
    $stmt->execute();

    // Update orders table
    $update_stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $update_stmt->bind_param("si", $status, $order_id);
    $update_stmt->execute();

    header("Location: staff_delivery.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Delivery - AgriMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Add Delivery</h2>
        <form method="POST">
            <div class="mb-3">
                <label>Order ID:</label>
                <input type="number" name="order_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Status:</label>
                <select name="status" class="form-select" required>
                    <option value="">Select</option>
                    <option value="delivered">Delivered</option>
                    <option value="failed">Failed</option>
                    <option value="delayed">Delayed</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Add Delivery</button>
            <a href="staff_delivery.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>