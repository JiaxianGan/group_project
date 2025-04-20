<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}

$vendor_id = isset($_SESSION['vendor_id']) ? $_SESSION['vendor_id'] : 1;

$query = "SELECT email, business_name, description, subscription_tier, contact FROM vendors WHERE vendor_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $vendor_id);
$stmt->execute();
$result = $stmt->get_result();
$vendors = $result->fetch_assoc();

$update_success = false; // Variable to track update success

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update vendor details
    $email = $_POST['email'];
    $business_name = $_POST['business_name'];
    $description = $_POST['description'];
    $subscription_tier = $_POST['subscription_tier'];
    $contact = $_POST['contact'];

    $update_query = "UPDATE vendors SET email=?, business_name=?, description=?, subscription_tier=?, contact=? WHERE vendor_id=?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssssss", $email, $business_name, $description, $subscription_tier, $contact, $vendor_id);
    
    if ($update_stmt->execute()) {
        $update_success = true; // Set success to true if update is successful
    }

    // Redirect to vendor_dashboard.php after update
    header("Location: vendors_dashboard.php?update=success");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('dashboard_background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .navbar {
            background-color:rgb(250, 3, 69) !important;
        }
        .navbar .nav-link {
            color:black !important;
        }
        .navbar .nav-link:hover {
            background-color:rgb(123, 248, 148);
            border-radius: 5px;
        }
        .container {
            padding: 40px;
            background: palegreen;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(212, 2, 215, 0.83);
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
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2>Your Profile</h2>
        
        <?php if (isset($_GET['update']) && $_GET['update'] == 'success'): ?>
            <div class="alert alert-success" role="alert">
                Your profile has been successfully updated!
            </div>
        <?php endif; ?>

        <form method="POST" action="">
        <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($vendors['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="business_name" class="form-label">Business Name</label>
                <input type="text" class="form-control" id="business_name" name="business_name" value="<?php echo htmlspecialchars($vendors['business_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($vendors['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="subscription_tier" class="form-label">Subscription Tier</label>
                <select class="form-select" id="subscription_tier" name="subscription_tier" required>
                    <option value="Basic" <?php echo ($vendors['subscription_tier'] == 'Basic') ? 'selected' : ''; ?>>Basic</option>
                    <option value="Premium" <?php echo ($vendors['subscription_tier'] == 'Premium') ? 'selected' : ''; ?>>Premium</option>
                    <option value="Enterprise" <?php echo ($vendors['subscription_tier'] == 'Enterprise') ? 'selected' : ''; ?>>Enterprise</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="contact" class="form-label">Contact Number</label>
                <input type="text" class="form-control" id="contact" name="contact" value="<?php echo htmlspecialchars($vendors['contact']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
            <a href="vendors_dashboard.php" class="btn btn-secondary">Back</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
