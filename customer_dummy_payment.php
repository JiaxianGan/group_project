<?php
$order_id = $_GET['order_id'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Gateway | AgriMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5 text-center">
    <h2 class="mb-4">Welcome to Dummy Payment Gateway</h2>
    <p>Your Order ID: <strong><?= htmlspecialchars($order_id); ?></strong></p>
    <p>Total will be shown on the next page (mocked).</p>

    <form action="payment_success.php" method="POST">
        <input type="hidden" name="order_id" value="<?= $order_id; ?>">
        <button class="btn btn-success mt-3">Pay Now</button>
    </form>
</div>
</body>
</html>
