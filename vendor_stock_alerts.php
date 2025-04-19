<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}
$vendor_id = isset($_SESSION['vendor_id']) ? $_SESSION['vendor_id'] : 1;


$query = "SELECT name, stock_quantity FROM products WHERE vendor_id = ?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("s", $vendor_id);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Alerts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('dashboard_background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .navbar {
            background-color: #155724 !important;
        }
        .navbar .nav-link {
            color: white !important;
        }
        .navbar .nav-link:hover {
            background-color: #1e7e34;
            border-radius: 5px;
        }
        .container {
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
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
                    <li class="nav-item"><a class="nav-link" href="vendor_reviews.php">Reviews</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendor_subscriptions.php">Subscriptions</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendor_analytics.php">Analytics</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Stock Alerts</h1>
        <p>Get notified when your stock is low.</p>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Current Stock</th>
                    <th>Low Stock Alert</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['stock_quantity']); ?></td>
                        <td>
                            <?php if ($product['stock_quantity'] < 10): // Set your low stock threshold ?>
                                <span class="text-danger">Low Stock</span>
                            <?php else: ?>
                                <span class="text-success">Sufficient Stock</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="vendors_dashboard.php" class="btn btn-secondary">Back</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
