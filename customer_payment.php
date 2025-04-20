<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: auth.php");
    exit();
}

$customer_id = $_SESSION['user_id'];
$cart_query = $conn->prepare("
    SELECT p.name, p.price, c.quantity, (p.price * c.quantity) AS subtotal 
    FROM cart c 
    JOIN products p ON c.product_id = p.product_id 
    WHERE c.customer_id = ?
");
$cart_query->bind_param("i", $customer_id);
$cart_query->execute();
$cart_result = $cart_query->get_result();

$total_amount = 0;
$cart_items = [];
while ($row = $cart_result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_amount += $row['subtotal'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Gateway | AgriMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .payment-box {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="payment-box mx-auto" style="max-width: 600px;">
        <h2 class="text-center mb-4"><i class="bi bi-credit-card-2-front-fill"></i> Secure Payment</h2>
        <h5>Order Summary</h5>
        <ul class="list-group mb-3">
            <?php foreach ($cart_items as $item): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= htmlspecialchars($item['name']); ?> (RM <?= number_format($item['price'], 2); ?> x <?= $item['quantity']; ?>)
                    <span>RM <?= number_format($item['subtotal'], 2); ?></span>
                </li>
            <?php endforeach; ?>
            <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                Total
                <span>RM <?= number_format($total_amount, 2); ?></span>
            </li>
        </ul>

        <form action="customer_payment_success.php" method="POST">
            <input type="hidden" name="total_amount" value="<?= $total_amount; ?>">
            <input type="hidden" name="datetime" value="<?= date('Y-m-d H:i:s'); ?>">
            <?php foreach ($cart_items as $item): ?>
                <input type="hidden" name="items[]" 
                    value="<?= htmlspecialchars($item['name']) . ' | RM ' . number_format($item['price'], 2) . ' x ' . $item['quantity'] . ' = RM ' . number_format($item['subtotal'], 2); ?>">
            <?php endforeach; ?>
            <button type="submit" class="btn btn-success w-100 mt-3">Pay Now</button>
        </form>
    </div>
</div>
</body>
</html>
