<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Order</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-success mb-4"><i class="fas fa-edit"></i> Edit Order</h3>
        <form action="update_status.php" method="POST">
            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">

            <div class="mb-3">
                <label for="customer_name" class="form-label"><i class="fas fa-user"></i> Customer Name</label>
                <input type="text" name="customer_name" class="form-control" required value="<?php echo $order['customer_name']; ?>">
            </div>

            <div class="mb-3">
                <label for="total_price" class="form-label"><i class="fas fa-money-bill-wave"></i> Total Price (RM)</label>
                <input type="number" step="0.01" name="total_price" class="form-control" required value="<?php echo $order['total_price']; ?>">
            </div>

            <div class="mb-3">
                <label for="status" class="form-label"><i class="fas fa-truck"></i> Order Status</label>
                <select name="status" class="form-select" required>
                    <option value="pending" <?php if ($order['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                    <option value="shipped" <?php if ($order['status'] == 'shipped') echo 'selected'; ?>>Shipped</option>
                    <option value="delivered" <?php if ($order['status'] == 'delivered') echo 'selected'; ?>>Delivered</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Order
            </button>
            <a href="orders.php" class="btn btn-secondary">
                <i class="fas fa-times-circle"></i> Cancel
            </a>
        </form>
    </div>
</body>
</html>
