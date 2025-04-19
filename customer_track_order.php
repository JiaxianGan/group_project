<?php
session_start();
include 'db_connect.php';

// Check if user is logged in and is a customer
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
    header("Location: auth.php");
    exit();
}

$username = $_SESSION['username'];

// Validate order ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid order ID.");
}

$order_id = $_GET['id'];

// Use prepared statement for secure fetching
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND customer_name = ?");
$stmt->bind_param("is", $order_id, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Order not found or you don't have permission to view this order.");
}

$order = $result->fetch_assoc();
$status = strtolower($order['status']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track Order - AgriMarket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .container {
            margin-top: 60px;
        }
        .step {
            text-align: center;
            position: relative;
            flex: 1;
        }
        .step i {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .step::before {
            content: '';
            position: absolute;
            top: 12px;
            left: -50%;
            width: 100%;
            height: 4px;
            background-color: #dee2e6;
            z-index: -1;
        }
        .step:first-child::before {
            content: none;
        }
        .step.active i, .step.active span {
            color: #0d6efd;
            font-weight: bold;
        }
        .step.active::before {
            background-color: #0d6efd;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h2><i class="bi bi-truck"></i> Track Order #<?= htmlspecialchars($order_id) ?></h2>
        <a href="customer_order_history.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle"></i> Back to Orders
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Order Progress</h5>
            <div class="d-flex justify-content-between">
                <?php
                $statuses = ['pending' => 'bi-hourglass-split', 'shipped' => 'bi-truck', 'delivered' => 'bi-box2-check'];
                $current_step = match($status) {
                    'delivered' => 3,
                    'shipped' => 2,
                    default => 1,
                };
                $step_names = ['Pending', 'Shipped', 'Delivered'];
                $icons = ['bi-hourglass-split', 'bi-truck', 'bi-box2-check'];
                foreach ($step_names as $i => $name):
                    $is_active = ($i + 1 <= $current_step);
                ?>
                    <div class="step <?= $is_active ? 'active' : '' ?>">
                        <i class="bi <?= $icons[$i] ?>"></i><br>
                        <span><?= $name ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Order Details</h5>
            <p><strong>Status:</strong> 
                <span class="badge bg-<?= $status === 'pending' ? 'warning text-dark' : ($status === 'shipped' ? 'info text-dark' : ($status === 'delivered' ? 'primary' : 'secondary')) ?>">
                    <?= ucfirst($status) ?>
                </span>
            </p>
            <p><strong>Placed on:</strong> <?= htmlspecialchars($order['order_date']) ?></p>
            <p><strong>Total:</strong> RM <?= number_format($order['total_price'], 2) ?></p>
            <hr>
            <p><strong>Shipping Address:</strong> <?= htmlspecialchars($order['shipping_address']) ?? 'N/A' ?></p>
            <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method']) ?? 'N/A' ?></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
