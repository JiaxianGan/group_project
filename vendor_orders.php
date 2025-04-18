<?php
session_start();

if (!isset($_SESSION['vendor_username'])) {
    header("Location: auth.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Add your custom styles here */
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">AgriMarket Vendor</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="vendor_dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendor_orders.php">Your Orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendor_profile.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Your Orders</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Example static data for orders
                $orders = [
                    ["id" => 1, "customer" => "John Doe", "product" => "Tomatoes", "quantity" => 10, "status" => "Delivered"],
                    ["id" => 2, "customer" => "Jane Smith", "product" => "Potatoes", "quantity" => 20, "status" => "Pending"],
                ];
                
                foreach ($orders as $index => $order) {
                    echo "<tr>
                            <td>" . ($index + 1) . "</td>
                            <td>" . $order['id'] . "</td>
                            <td>" . $order['customer'] . "</td>
                            <td>" . $order['product'] . "</td>
                            <td>" . $order['quantity'] . "</td>
                            <td>" . $order['status'] . "</td>
                            <td><a href='view_order.php?id=" . $order['id'] . "' class='btn btn-info'>View</a></td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
