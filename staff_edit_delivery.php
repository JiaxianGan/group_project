<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    $stmt = $conn->prepare("SELECT status FROM order_tracking WHERE order_id = ? ORDER BY updated_at DESC LIMIT 1");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->bind_result($current_status);
    $stmt->fetch();
    $stmt->close();
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    // Insert new tracking status
    $stmt = $conn->prepare("INSERT INTO order_tracking (order_id, status) VALUES (?, ?)");
    $stmt->bind_param("is", $order_id, $new_status);
    $stmt->execute();

    // Update orders table
    $update_stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $update_stmt->bind_param("si", $new_status, $order_id);
    $update_stmt->execute();

    header("Location: staff_delivery.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Delivery - AgriMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Edit Delivery Status</h2>
        <form method="POST">
            <input type="hidden" name="order_id" value="<?= htmlspecialchars($_GET['order_id']) ?>">
            <div class="mb-3">
                <label>Current Status:</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($current_status ?? 'Not updated') ?>" readonly>
            </div>
            <div class="mb-3">
                <label>New Status:</label>
                <select name="status" class="form-select" required>
                    <option value="">Select</option>
                    <option value="delivered">Delivered</option>
                    <option value="failed">Failed</option>
                    <option value="delayed">Delayed</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Status</button>
            <a href="staff_delivery.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>