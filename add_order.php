<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = $_POST['customer_name'];
    $total_price = $_POST['total_price'];
    $status = $_POST['status'];
    $order_date = date('Y-m-d');

    $query = "INSERT INTO orders (customer_name, total_price, status, order_date) VALUES ('$customer_name', '$total_price', '$status', '$order_date')";
    if ($conn->query($query) === TRUE) {
        echo "New order added successfully!";
        header("Location: orders.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Order</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-primary"><i class="fas fa-plus-circle"></i> Add New Order</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="customer_name" class="form-label"><i class="fas fa-user"></i> Customer Name</label>
                <input type="text" class="form-control" name="customer_name" required>
            </div>
            <div class="mb-3">
                <label for="total_price" class="form-label"><i class="fas fa-money-bill-wave"></i> Total Price</label>
                <input type="number" class="form-control" name="total_price" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label"><i class="fas fa-truck"></i> Status</label>
                <select class="form-select" name="status">
                    <option value="pending">Pending</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-check-circle"></i> Add Order
            </button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
