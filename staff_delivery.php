<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}
include 'db_connect.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_status'])) {
        $order_id = $_POST['order_id'];
        $status = $_POST['status'];
        $stmt = $conn->prepare("INSERT INTO order_tracking (order_id, status) VALUES (?, ?)");
        $stmt->bind_param("is", $order_id, $status);
        $stmt->execute();
        $update_stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
        $update_stmt->bind_param("si", $status, $order_id);
        $update_stmt->execute();
    }
    if (isset($_POST['edit_status'])) {
        $tracking_id = $_POST['tracking_id'];
        $status = $_POST['status'];
        $stmt = $conn->prepare("UPDATE order_tracking SET status = ? WHERE tracking_id = ?");
        $stmt->bind_param("si", $status, $tracking_id);
        $stmt->execute();
    }
    if (isset($_POST['delete_status'])) {
        $tracking_id = $_POST['tracking_id'];
        $stmt = $conn->prepare("DELETE FROM order_tracking WHERE tracking_id = ?");
        $stmt->bind_param("i", $tracking_id);
        $stmt->execute();
    }
}
$sql = "SELECT o.order_id, u.username AS customer_name, u.address AS delivery_address, 
               oi.product_id, oi.quantity, p.name AS product_name
        FROM orders o
        JOIN users u ON o.customer_id = u.user_id
        JOIN order_items oi ON o.order_id = oi.order_id
        JOIN products p ON oi.product_id = p.product_id
        WHERE o.status = 'Pending'";
$result = $conn->query($sql);
$status_sql = "SELECT order_id, status FROM (
                    SELECT * FROM order_tracking ORDER BY updated_at DESC
               ) as latest GROUP BY order_id";
$status_result = $conn->query($status_sql);
$order_statuses = [];
while ($row = $status_result->fetch_assoc()) {
    $order_statuses[$row['order_id']] = $row['status'];
}
$trackings = $conn->query("SELECT * FROM order_tracking ORDER BY updated_at DESC");
$edit_id = isset($_GET['edit']) ? intval($_GET['edit']) : null;
$edit_data = null;
if ($edit_id) {
    $stmt = $conn->prepare("SELECT * FROM order_tracking WHERE tracking_id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $edit_data = $stmt->get_result()->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delivery Management - AgriMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('products_background.jpg');
            background-size: cover;
            background-attachment: fixed;
        }
        .navbar {
            background-color: #155724 !important;
        }
        .navbar .nav-link {
            color: white !important;
        }
        .navbar .nav-link:hover {
            background-color: #1e7e34;
            border-radius: 5px;
        }
        .table thead {
            background-color: #155724;
            color: white;
        }
        .status-badge {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#"><i class="fas fa-tractor me-2"></i>AgriMarket</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="staff_dashboard.php">Staff Panel</a></li>
                <li class="nav-item"><a class="nav-link active" href="staff_delivery.php">Delivery</a></li>
                <li class="nav-item"><a class="nav-link" href="staff_products.php">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="staff_reports.php">Reports</a></li>
                <li class="nav-item"><a class="nav-link" href="staff_profile.php">Profile</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5 bg-white p-5 rounded-4 shadow">
    <hr class="my-4">
    <h3><i class="fas fa-list me-2"></i>All Delivery Status Records</h3>
    <div style="text-align: right; margin-bottom: 10px;">
        <a href="staff_add_delivery.php" class="btn btn-success">
            <i class="fas fa-plus"></i> Add Delivery
        </a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tracking ID</th>
                <th>Order ID</th>
                <th>Status</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($trackings && $trackings->num_rows > 0): ?>
            <?php while ($t = $trackings->fetch_assoc()): ?>
                <?php if ($edit_id === intval($t['tracking_id'])): ?>
                    <tr class="table-warning">
                        <form method="POST">
                            <td><?= $t['tracking_id'] ?><input type="hidden" name="tracking_id" value="<?= $t['tracking_id'] ?>"></td>
                            <td><?= $t['order_id'] ?></td>
                            <td>
                                <select name="status" class="form-select form-select-sm" required>
                                    <option value="delivering" <?= $t['status'] == 'delivering' ? 'selected' : '' ?>>Delivering</option>
                                    <option value="delivered" <?= $t['status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                    <option value="delayed" <?= $t['status'] == 'delayed' ? 'selected' : '' ?>>Delayed</option>
                                    <option value="failed" <?= $t['status'] == 'failed' ? 'selected' : '' ?>>Failed</option>
                                </select>
                            </td>
                            <td><?= $t['updated_at'] ?></td>
                            <td>
                                <button type="submit" name="edit_status" class="btn btn-primary btn-sm">Save</button>
                                <a href="staff_delivery.php" class="btn btn-secondary btn-sm">Cancel</a>
                            </td>
                        </form>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td><?= $t['tracking_id'] ?></td>
                        <td><?= $t['order_id'] ?></td>
                        <td><?= ucfirst($t['status']) ?></td>
                        <td><?= $t['updated_at'] ?></td>
                        <td>
                            <a href="staff_delivery.php?edit=<?= $t['tracking_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="tracking_id" value="<?= $t['tracking_id'] ?>">
                                <button type="submit" name="delete_status" class="btn btn-danger btn-sm" onclick="return confirm('Delete this record?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5" class="text-center">No delivery statuses found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>