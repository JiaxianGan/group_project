<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: auth.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items for current user
$sql = "SELECT c.*, p.name, p.price, p.image_url FROM cart c 
        JOIN products p ON c.product_id = p.product_id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart | AgriMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Your Shopping Cart</h2>
    <table class="table table-bordered mt-4">
        <thead class="table-success">
            <tr>
                <th>Product</th>
                <th>Image</th>
                <th>Price (RM)</th>
                <th>Quantity</th>
                <th>Total (RM)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total_price = 0;
            if ($result->num_rows > 0): 
                while ($row = $result->fetch_assoc()): 
                    $subtotal = $row['price'] * $row['quantity'];
                    $total_price += $subtotal;
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><img src="product_images/<?= htmlspecialchars($row['image_url']); ?>" width="80"></td>
                    <td><?= number_format($row['price'], 2); ?></td>
                    <td><?= $row['quantity']; ?></td>
                    <td><?= number_format($subtotal, 2); ?></td>
                </tr>
            <?php endwhile; ?>
            <tr>
                <td colspan="4" class="text-end"><strong>Total:</strong></td>
                <td><strong>RM <?= number_format($total_price, 2); ?></strong></td>
            </tr>
            <?php else: ?>
                <tr><td colspan="5" class="text-center">Your cart is empty.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="mt-4 d-flex justify-content-between">
        <a href="customer_product_list.php" class="btn btn-outline-secondary">← Continue Shopping</a>
        <?php if ($total_price > 0): ?>
            <a href="checkout.php" class="btn btn-success">Proceed to Payment →</a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
