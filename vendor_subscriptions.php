<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}

// Fetch subscriptions for the logged-in vendor
$vendor_id = isset($_SESSION['vendor_id']) ? $_SESSION['vendor_id'] : 1;

$query = "SELECT id, plan_name, price, duration FROM subscriptions WHERE vendor_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$result = $stmt->get_result();
$subscriptions = $result->fetch_all(MYSQLI_ASSOC);

// Handle deleting a subscription
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM subscriptions WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $delete_id);
    $delete_stmt->execute();

    header("Location: vendor_subscriptions.php");
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
        body {
            background-image: url('dashboard_background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .navbar {
            background-color:rgba(3, 250, 139, 0.83) !important;
        }
        .navbar .nav-link {
            color:black !important;
        }
        .navbar .nav-link:hover {
            background-color:rgb(148, 123, 248);
            border-radius: 5px;
        }
        .container {
            padding: 40px;
            background: palegoldenrod;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(2, 215, 183, 0.83);
        }
        .navbar-brand {
            color:rgb(26, 21, 1); /* Change this to your desired color */
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
                    <li class="nav-item"><a class="nav-link" href="vendors_dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendor_product.php">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendor_orders.php">Orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendor_stock_alerts.php">Stock Alerts</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendor_subscriptions.php">Subscriptions</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendor_staff.php">Staff Management</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Manage Subscriptions</h1>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Plan Name</th>
                    <th>Price</th>
                    <th>Duration (Months)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subscriptions as $subscription): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($subscription['plan_name']); ?></td>
                        <td><?php echo htmlspecialchars($subscription['price']); ?></td>
                        <td><?php echo htmlspecialchars($subscription['duration']); ?></td>
                        <td>
                            <a href="edit_subscription.php?id=<?php echo $subscription['id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="vendor_subscriptions.php?delete_id=<?php echo $subscription['id']; ?>" class="btn btn-danger">Delete</a>
                            </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="add_subscription.php" class="btn btn-success">Add New Subscription Plan</a>
        <a href="vendors_dashboard.php" class="btn btn-secondary">Back</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
