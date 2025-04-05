<?php
session_start();
include 'db_connect.php';

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure all required fields are set
    $user_id = $_POST['user_id'] ?? null; // Use null coalescing operator to avoid undefined index
    $vendor_name = $_POST['vendor_name'] ?? null;
    $email = $_POST['email'] ?? null;
    $contact = $_POST['contact'] ?? null;
    $business_name = $_POST['business_name'] ?? null;
    $description = $_POST['description'] ?? null;
    $subscription_tier = $_POST['subscription_tier'] ?? null;

    // Check if user_id is not null
    if ($user_id === null) {
        $error = "User  ID cannot be null.";
    } else {
        $stmt = $conn->prepare("INSERT INTO vendors (user_id, vendor_name, email, contact, business_name, description, subscription_tier) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $user_id, $vendor_name, $email, $contact, $business_name, $description, $subscription_tier);

        if ($stmt->execute()) {
            header("Location: vendors.php");
            exit();
        } else {
            $error = "Failed to add vendor: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Vendor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('vendors_background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-top: 50px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
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
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#">AgriMarket</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse w-100" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="orders.php">Orders</a></li>
                <li class="nav-item"><a class="nav-link active" href="vendors.php">Vendors</a></li>
                <li class="nav-item"><a class="nav-link" href="reports.php">Reports</a></li>
                <li class="nav-item"><a class="nav-link" href="settings.php">Settings</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Form -->
<div class="container">
    <div class="form-container">
        <h2 class="text-success">Add New Vendor</h2>
        <p>Register a new vendor and fill out all required fields.</p>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="post" action="">
            

            <div class="mb-3">
            <label for="user_id" class="form-label">User  ID</label>
            <input type="number" class="form-control" id="user_id" name="user_id" required>
            </div>

            <div class="mb-3">
                <label for="vendor_name" class="form-label">Vendor Name</label>
                <input type="text" class="form-control" id="vendor_name" name="vendor_name" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="contact" class="form-label">Contact Number</label>
                <input type="text" class="form-control" id="contact" name="contact" required>
            </div>

            <div class="mb-3">
                <label for="business_name" class="form-label">Business Name</label>
                <input type="text" class="form-control" id="business_name" name="business_name" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Business Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="subscription_tier" class="form-label">Subscription Tier</label>
                <select class="form-select" id="subscription_tier" name="subscription_tier" required>
                    <option value="">Select Tier</option>
                    <option value="Basic">Basic</option>
                    <option value="Premium">Premium</option>
                    <option value="Enterprise">Enterprise</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Register Vendor</button>
            <a href="vendors.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
