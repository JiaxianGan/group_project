<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: auth.php");
    exit();
}

$customer_id = $_SESSION['user_id'];
$items = $_POST['items'] ?? [];
$total_amount = $_POST['total_amount'] ?? 0;
$datetime = $_POST['datetime'] ?? date('Y-m-d H:i:s');

// Clear cart after payment
$clear_cart = $conn->prepare("DELETE FROM cart WHERE customer_id = ?");
$clear_cart->bind_param("i", $customer_id);
$clear_cart->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Success | AgriMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
        }
        .receipt-box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
            max-width: 700px;
            margin: auto;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="receipt-box">
        <h2 class="text-success text-center mb-4"><i class="bi bi-check-circle-fill"></i> Payment Successful!</h2>
        <p><strong>Date & Time:</strong> <?= htmlspecialchars($datetime); ?></p>
        <p><strong>Customer ID:</strong> <?= $customer_id; ?></p>

        <h5 class="mt-4">🧾 Receipt Details</h5>
        <ul class="list-group mb-3">
            <?php foreach ($items as $item): ?>
                <li class="list-group-item"><?= htmlspecialchars($item); ?></li>
            <?php endforeach; ?>
            <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                Total Paid
                <span>RM <?= number_format($total_amount, 2); ?></span>
            </li>
        </ul>

        <div class="text-center">
            <a href="order_history.php" class="btn btn-primary mt-3 me-2">📄 View Order History</a>
            <a href="customer_dashboard.php" class="btn btn-secondary mt-3">🏠 Back to Dashboard</a>
        </div>
    </div>
</div>
</body>
</html>
