<?php
session_start();
include 'db_connect.php';

// Check if user is logged in and is a customer
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
    header("Location: auth.php");
    exit();
}

$username = $_SESSION['username'];

// Use prepared statement for security
$stmt = $conn->prepare("SELECT * FROM orders WHERE customer_id = ? ORDER BY order_datetime DESC");
$stmt->bind_param("i", $_SESSION['user_id']);  // Ensure correct user_id is passed
$stmt->execute();
$orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders - AgriMarket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
        }
        .container {
            margin-top: 60px;
        }
        h2 {
            color: #155724;
            font-weight: bold;
        }
        .badge-status {
            padding: 5px 10px;
            font-size: 0.9rem;
        }
        .badge-pending {
            background-color: #ffc107;
            color: #333;
        }
        .badge-paid {
            background-color: #28a745;
        }
        .badge-shipped {
            background-color: #17a2b8;
        }
        .badge-delivered {
            background-color: #007bff;
        }
        .badge-cancelled {
            background-color: #dc3545;
        }
        .badge-info {
            background-color: #6c757d;
        }
        .order-status-step {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
        }
        .order-status-step .step {
            text-align: center;
        }
        .order-status-step .step i {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .order-status-step .step.active {
            color: #007bff;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-bag-check"></i> My Order History</h2>
        <a href="customer_dashboard.php" class="btn btn-outline-success"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
    </div>

    <?php if ($orders->num_rows > 0): ?>
        <?php while ($row = $orders->fetch_assoc()): ?>
            <?php
                // Handle order status and corresponding badge
                $status = strtolower($row['status']);
                $status_classes = [
                    'pending' => 'badge-pending',
                    'paid' => 'badge-paid',
                    'shipped' => 'badge-shipped',
                    'delivered' => 'badge-delivered',
                    'cancelled' => 'badge-cancelled',
                ];
                $badgeClass = $status_classes[$status] ?? 'badge-info';
            ?>

            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        Order #<?= $row['order_id'] ?> - <?= date('d M Y, H:i', strtotime($row['order_datetime'])) ?>
                    </h5>
                    <p>Status: <span class="badge <?= $badgeClass ?> badge-status text-uppercase"><?= ucfirst($status) ?></span></p>
                    <p>Total: RM <?= number_format($row['total_amount'], 2) ?></p>

                    <!-- Order Status Stepper -->
                    <div class="order-status-step">
                        <div class="step <?= $status === 'pending' ? 'active' : '' ?>">
                            <i class="bi bi-hourglass-split"></i>
                            <br>Pending
                        </div>
                        <div class="step <?= $status === 'shipped' ? 'active' : '' ?>">
                            <i class="bi bi-truck"></i>
                            <br>Shipped
                        </div>
                        <div class="step <?= $status === 'delivered' ? 'active' : '' ?>">
                            <i class="bi bi-box2-check"></i>
                            <br>Delivered
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="d-flex justify-content-between mt-3">
                        <a href="track_order.php?id=<?= $row['order_id'] ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-truck"></i> Track Order
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-info"><i class="bi bi-info-circle"></i> You haven’t placed any orders yet.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
