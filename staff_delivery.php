<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}

include 'db_connect.php';

// Handle delivery status update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Insert into order_tracking table
    $stmt = $conn->prepare("INSERT INTO order_tracking (order_id, status) VALUES (?, ?)");
    $stmt->bind_param("is", $order_id, $status);
    $stmt->execute();

    // Optional: Also update orders table with latest status
    $update_stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $update_stmt->bind_param("si", $status, $order_id);
    $update_stmt->execute();
}

// Fetch all pending orders with customer & product details
$sql = "SELECT o.order_id, u.username AS customer_name, u.address AS delivery_address, 
               oi.product_id, oi.quantity, p.name AS product_name
        FROM orders o
        JOIN users u ON o.customer_id = u.user_id
        JOIN order_items oi ON o.order_id = oi.order_id
        JOIN products p ON oi.product_id = p.product_id
        WHERE o.status = 'Pending'";
$result = $conn->query($sql);

// Fetch latest delivery status for each order
$status_sql = "SELECT order_id, status FROM (
                    SELECT * FROM order_tracking ORDER BY updated_at DESC
               ) as latest GROUP BY order_id";
$status_result = $conn->query($status_sql);

$order_statuses = [];
while ($row = $status_result->fetch_assoc()) {
    $order_statuses[$row['order_id']] = $row['status'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Management - AgriMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('products_background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .navbar {
            background-color: #155724 !important;
        }
        .navbar .nav-link {
            color: white !important;
            font-weight: 500;
        }
        .navbar .nav-link:hover {
            background-color: #1e7e34;
            border-radius: 5px;
        }
        .table thead {
            background-color: #155724;
            color: white;
        }
        .container {
            margin-top: 50px;
        }
        .status-badge {
            font-size: 0.9rem;
        }
        select.form-select {
            padding: 4px 8px;
        }
        .btn-sm {
            padding: 4px 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="fas fa-tractor me-2"></i>AgriMarket</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="staff_dashboard.php"><i class="fas fa-home me-1"></i>Staff Panel</a></li>
                    <li class="nav-item"><a class="nav-link active" href="staff_delivery.php"><i class="fas fa-truck me-1"></i>Delivery</a></li>
                    <li class="nav-item"><a class="nav-link" href="staff_products.php"><i class="fas fa-warehouse me-1"></i>Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="staff_reports.php"><i class="fas fa-chart-line me-1"></i>Reports</a></li>
                    <li class="nav-item"><a class="nav-link" href="staff_profile.php"><i class="fas fa-cog me-1"></i>Profile</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="bg-white p-5 rounded-4 shadow">
            <h3 class="mb-4"><i class="fas fa-truck me-2"></i>Delivery Management</h3>
            <table class="table table-hover table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Address</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Delivery Status</th>
                        <th>Update Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['order_id'] ?></td>
                                <td><?= htmlspecialchars($row['customer_name']) ?></td>
                                <td><?= htmlspecialchars($row['delivery_address']) ?></td>
                                <td><?= htmlspecialchars($row['product_name']) ?></td>
                                <td><?= $row['quantity'] ?></td>
                                <td>
                                    <?php
                                        $status = $order_statuses[$row['order_id']] ?? 'Not updated';
                                        $badge_class = match ($status) {
                                            'delivered' => 'success',
                                            'failed' => 'danger',
                                            'delayed' => 'warning',
                                            default => 'secondary'
                                        };
                                    ?>
                                    <span class="badge bg-<?= $badge_class ?> status-badge"><?= ucfirst($status) ?></span>
                                </td>
                                <td>
                                    <form method="POST" action="staff_delivery.php" class="d-flex align-items-center gap-2">
                                        <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
                                        <select name="status" class="form-select form-select-sm" required>
                                            <option value="">Select</option>
                                            <option value="delivered">Delivered</option>
                                            <option value="failed">Failed</option>
                                            <option value="delayed">Delayed</option>
                                        </select>
                                        <button type="submit" class="btn btn-success btn-sm">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No pending orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>