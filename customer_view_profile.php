<?php
session_start();
include 'db_connect.php';

// Check if user is logged in and is a customer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: auth.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get customer profile data
$sql = "SELECT username, email, phone, address FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    echo "<div style='padding:20px;color:red;'>User not found or database error.</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Profile - AgriMarket Solutions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body style="background-color: #f8f9fa;">
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-success text-white">
            <h4><i class="bi bi-person-lines-fill"></i> Profile Details</h4>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label fw-bold"><i class="bi bi-person-fill"></i> Username:</label>
                <div class="form-control bg-light"><?= htmlspecialchars($user['username']); ?></div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold"><i class="bi bi-envelope-fill"></i> Email:</label>
                <div class="form-control bg-light"><?= htmlspecialchars($user['email']); ?></div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold"><i class="bi bi-phone-fill"></i> Phone:</label>
                <div class="form-control bg-light"><?= htmlspecialchars($user['phone']); ?></div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold"><i class="bi bi-geo-alt-fill"></i> Address:</label>
                <div class="form-control bg-light"><?= htmlspecialchars($user['address']); ?></div>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="customer_edit_profile.php" class="btn btn-primary">
                <i class="bi bi-pencil-square"></i> Edit Profile
            </a>
            <a href="customer_dashboard.php" class="btn btn-outline-secondary ms-2">
                <i class="bi bi-house-door-fill"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
