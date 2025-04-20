<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // CHANGE: Changed to redirect instead of JSON response
    header("Location: admin_dashboard.php?message=Unauthorized access&type=danger");
    exit();
}

// CHANGE: Switched from JSON to $_POST
$user_id = intval($_POST['user_id'] ?? 0);
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// NEW: Server-side validation
if ($user_id <= 0) {
    header("Location: admin_dashboard.php?message=Invalid user ID&type=danger");
    exit();
}

if (strlen($new_password) < 8) {
    header("Location: admin_dashboard.php?message=Password must be at least 8 characters&type=danger");
    exit();
}

if ($new_password !== $confirm_password) {
    header("Location: admin_dashboard.php?message=Passwords do not match&type=danger");
    exit();
}

// CHANGE: Simplified password update
$password_hash = password_hash($new_password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
$stmt->bind_param("si", $password_hash, $user_id);

if ($stmt->execute()) {
    $stmt->close();
    // CHANGE: Redirect with success message
    header("Location: admin_dashboard.php?message=Password reset successfully&type=success");
} else {
    $stmt->close();
    // CHANGE: Redirect with error message
    header("Location: admin_dashboard.php?message=Error resetting password: " . urlencode($conn->error) . "&type=danger");
}
?>