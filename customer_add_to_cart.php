<?php
session_start();
include 'db_connect.php';

// Ensure customer is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: auth.php");
    exit();
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

$product_details = [];

if (!empty($cart)) {
    $placeholders = implode(',', array_fill(0, count($cart), '?'));
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id IN ($placeholders)");
    $stmt->bind_param(str_repeat('i', count($cart)), ...array_keys($cart));
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $product_details[$row['product_id']] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart | AgriMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f9f9f9; padding: 20px; }
        .table img { height: 80px; object-fit: contain; }
        .btn-action { padding: 5px 10px; margin: 2px; }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4">Your Shopping Cart</h2>

    <?php if (!empty($cart) && !empty($product_details)): ?>
        <form action="customer_update_cart.php" method="post">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-success">
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price (RM)</th>
                        <th>Quantity</th>
                        <th>Total (RM)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $grand_total = 0; ?>
                    <?php foreach ($cart as $product_id => $qty): ?>
                        <?php
                        $product = $product_details[$product_id];
                        $image = !empty($product['image_url']) ? 'product_images/' . $product['image_url'] : 'product_images/default-placeholder.png';
                        $total = $product['price'] * $qty;
                        $grand_total += $total;
                        ?>
                        <tr>
                            <td><img src="<?= $image ?>" alt=""></td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= number_format($product['price'], 2) ?></td>
                            <td>
                                <input type="number" name="quantities[<?= $product_id ?>]" value="<?= $qty ?>" min="1" max="<?= $product['stock_quantity'] ?>" class="form-control" required>
                            </td>
                            <td><?= number_format($total, 2) ?></td>
                            <td>
                                <button type="submit" name="update[<?= $product_id ?>]" class="btn btn-primary btn-action">Update</button>
                                <button type="submit" name="delete[<?= $product_id ?>]" class="btn btn-danger btn-action">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="table-secondary">
                        <td colspan="4" class="text-end"><strong>Grand Total:</strong></td>
                        <td colspan="2"><strong>RM <?= number_format($grand_total, 2) ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </form>

        <div class="d-flex justify-content-between mt-4">
            <a href="customer_product_list.php" class="btn btn-outline-success">← Back to Products</a>
            <a href="customer_payment.php" class="btn btn-success">Proceed to Pay →</a>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Your cart is currently empty.</div>
        <a href="customer_product_list.php" class="btn btn-outline-success">← Shop Now</a>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
