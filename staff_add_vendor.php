<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}

$successMsg = $errorMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vendorName = $_POST['vendor_name'];
    $email = $_POST['email'];
    $businessName = $_POST['business_name'];
    $description = $_POST['description'];
    $subscriptionTier = $_POST['subscription_tier'];
    $contact = $_POST['contact'];
    $user_id = 0; // Change to actual user_id from your system if needed

    $sql = "INSERT INTO vendors (user_id, vendor_name, email, business_name, description, subscription_tier, contact)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssss", $user_id, $vendorName, $email, $businessName, $description, $subscriptionTier, $contact);

    if ($stmt->execute()) {
        $successMsg = "Vendor added successfully!";
    } else {
        $errorMsg = "Error adding vendor: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Vendor - AgriMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('vendors_background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .container {
            margin-top: 80px;
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            max-width: 700px;
        }

        .btn-success {
            background-color: #155724;
            border: none;
        }

        .btn-success:hover {
            background-color: #1e7e34;
        }
    </style>
</head>
<body>

<div class="container shadow">
    <h2 class="mb-4 fw-bold text-success">Add New Vendor</h2>

    <?php if ($successMsg): ?>
        <div class="alert alert-success"><?php echo $successMsg; ?></div>
    <?php elseif ($errorMsg): ?>
        <div class="alert alert-danger"><?php echo $errorMsg; ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Vendor Name</label>
            <input type="text" name="vendor_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Business Name</label>
            <input type="text" name="business_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Contact</label>
            <input type="text" name="contact" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Subscription Tier</label>
            <select name="subscription_tier" class="form-select" required>
                <option value="basic">Basic</option>
                <option value="premium">Premium</option>
                <option value="enterprise">Enterprise</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Add Vendor</button>
        <a href="staff_vendors.php" class="btn btn-secondary">Back</a>
    </form>
</div>

</body>
</html>