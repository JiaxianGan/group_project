<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}
$vendor_id = isset($_SESSION['vendor_id']) ? $_SESSION['vendor_id'] : 1;

$query = "SELECT * FROM products WHERE vendor_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$result = $stmt->get_result();



?>
<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-info">
        <?php
        echo htmlspecialchars($_SESSION['message']);
        unset($_SESSION['message']);
        ?>
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
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
                    <li class="nav-item"><a class="nav-link" href="vendor_orders.php">Orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendor_stock_alerts.php">Stock Alerts</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendor_reviews.php">Reviews</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendor_subscriptions.php">Subscriptions</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendor_analytics.php">Analytics</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1>Manage Your Products</h1>
        <a href="add_product_vendor.php" class="btn btn-success">Add New Product</a>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Vendor ID</th>
                    <th>Product Name</th>
                    <th>Product Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>

                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['product_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['vendor_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                        <td><?php echo htmlspecialchars($row['price']); ?></td>
                        <td><?php echo htmlspecialchars($row['stock_quantity']); ?></td>
                        <td>
                            <a href="edit_vendor_product.php?id=<?php echo $row['product_id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="delete_vendor_product.php?id=<?php echo $row['product_id']; ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>       
            
        </table>
        <a href="vendors_dashboard.php" class="btn btn-secondary">Back</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
