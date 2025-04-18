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
    <title>Manage Subscriptions</title>
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
                    <li class="nav-item"><a class="nav-link" href="vendor_profile.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendor_products.php">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendor_orders.php">Orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendor_stock_alerts.php">Stock Alerts</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendor_reviews.php">Reviews</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendor_analytics.php">Analytics</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1>Manage Subscriptions</h1>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Plan Name</th>
                    <th>Price</th>
                    <th>Duration</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Sample data, replace with dynamic data from your database -->
                <tr>
                    <td>Basic Plan</td>
                    <td>$10/month</td>
                    <td>1 Month</td>
                    <td>
                        <a href="edit_subscription.php?id=1 " class="btn btn-warning">Edit</a>
                        <a href="delete_subscription.php?id=1" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
                <tr>
                    <td>Premium Plan</td>
                    <td>$25/month</td>
                    <td>1 Month</td>
                    <td>
                        <a href="edit_subscription.php?id=2" class="btn btn-warning">Edit</a>
                        <a href="delete_subscription.php?id=2" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
                <!-- Repeat for other subscription plans -->
            </tbody>
        </table>
        <a href="add_subscription.php" class="btn btn-success">Add New Subscription Plan</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
