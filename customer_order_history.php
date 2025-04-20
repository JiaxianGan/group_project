<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: auth.php");
    exit();
}

$customer_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Order History | AgriMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color:rgb(248, 248, 248);
            font-family: 'Segoe UI', sans-serif;
        }
        .order-card {
            border-radius: 15px;
            box-shadow: 0 2px 12px rgba(0, 128, 0, 0.1);
            background-color: #fff;
            border: none;
        }
        .order-header {
            background-color:rgb(93, 203, 118);
            padding: 10px 20px;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            font-weight: 600;
        }
        .order-footer {
            background-color:rgb(93, 203, 118);
            padding: 10px 20px;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
            text-align: right;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4 text-center text-success"><i class="bi bi-bag-check-fill"></i> My Order History</h2>

    <?php
    $stmt = $conn->prepare("SELECT * FROM orders WHERE customer_id = ? ORDER BY order_datetime DESC");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0): 
        while ($row = $result->fetch_assoc()):
    ?>
        <div class="card mb-4 order-card">
            <div class="order-header">
                🧾 Order #<?= $row['order_id']; ?> — <span class="text-primary"><?= htmlspecialchars($row['status']); ?></span>
            </div>
            <div class="card-body">
                <p><strong>Date:</strong> <?= $row['order_datetime']; ?></p>
                <p><strong>Customer Name:</strong> <?= htmlspecialchars($row['customer_name']); ?></p>
                <p><strong>Items:</strong> <?= nl2br(htmlspecialchars($row['order_details'])); ?></p>
                <p><strong>Payment Method:</strong> <?= htmlspecialchars($row['payment_method']); ?></p>
                <p class="fw-bold">Total Amount: <span class="text-success">RM <?= number_format($row['total_amount'], 2); ?></span></p>
                <a href="customer_track_order.php?order_id=<?= $row['order_id']; ?>" class="btn btn-outline-success mt-3">
                    <i class="bi bi-truck"></i> Track Order
                </a>
            </div>
            <div class="order-footer">
                <small>Thank you for shopping with AgriMarket!</small>
            </div>
        </div>
    <?php endwhile; else: ?>
        <div class="alert alert-warning text-center">
            You have not made any orders yet.
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="customer_dashboard.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>
</body>
</html>
