<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: auth.php");
    exit();
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$product_details = [];
$grand_total = 0;

if (!empty($cart)) {
    $placeholders = implode(',', array_fill(0, count($cart), '?'));
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id IN ($placeholders)");
    $stmt->bind_param(str_repeat('i', count($cart)), ...array_keys($cart));
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $product_details[$row['product_id']] = $row;
        $grand_total += $row['price'] * $cart[$row['product_id']];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment | AgriMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; padding: 30px; }
        .payment-box { background: #fff; border-radius: 10px; padding: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="container">
    <div class="payment-box">
        <h2 class="mb-4">Payment Summary</h2>

        <?php if (!empty($product_details)): ?>
            <p><strong>Total Amount to Pay:</strong> RM <?= number_format($grand_total, 2) ?></p>

            <form action="customer_payment_success.php" method="post">
                <input type="hidden" name="total_amount" value="<?= $grand_total ?>">

                <div class="mb-3">
                    <label for="payment_method" class="form-label">Select Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-select" required>
                        <option value="">-- Choose --</option>
                        <option value="credit_card">Credit / Debit Card</option>
                        <option value="mobile_banking">Mobile Banking</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="cod">Cash on Delivery</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Confirm & Pay</button>
                <a href="customer_add_to_cart.php" class="btn btn-secondary ms-2">Back to Cart</a>
            </form>
        <?php else: ?>
            <div class="alert alert-warning">Your cart is empty. Please add products before proceeding to payment.</div>
            <a href="customer_product_list.php" class="btn btn-primary">Shop Now</a>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
