<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // CHANGE: Added anchor to redirect
    header("Location: admin_dashboard.php?message=Unauthorized access&type=danger#user-management-section");
    exit();
}

// CHANGE: Enable error logging for debugging
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

// CHANGE: Switched from JSON to $_POST
$user_id = intval($_POST['user_id'] ?? 0);
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$role = trim($_POST['role'] ?? '');

// NEW: Log input data for debugging
error_log("Edit User Input: user_id=$user_id, username=$username, email=$email, phone=$phone, role=$role");

// NEW: Server-side validation
if ($user_id <= 0) {
    header("Location: admin_dashboard.php?message=Invalid user ID&type=danger#user-management-section");
    exit();
}

if (empty($email) || empty($role)) {
    header("Location: admin_dashboard.php?message=Email and role are required&type=danger#user-management-section");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: admin_dashboard.php?message=Invalid email format&type=danger#user-management-section");
    exit();
}

// NEW: Check if user exists
$stmt = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    $stmt->close();
    header("Location: admin_dashboard.php?message=User not found&type=danger#user-management-section");
    exit();
}
$stmt->close();

// NEW: Check for duplicate email
$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
$stmt->bind_param("si", $email, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $stmt->close();
    header("Location: admin_dashboard.php?message=Email already in use&type=danger#user-management-section");
    exit();
}
$stmt->close();

// CHANGE: Simplified update with prepared statement
$stmt = $conn->prepare("UPDATE users SET email = ?, phone = ?, role = ? WHERE user_id = ?");
$stmt->bind_param("sssi", $email, $phone, $role, $user_id);

if ($stmt->execute()) {
    // CHANGE: Simplified vendor handling
    if ($role === 'vendor') {
        $check_stmt = $conn->prepare("SELECT vendor_id FROM vendors WHERE user_id = ?");
        $check_stmt->bind_param("i", $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows === 0) {
            $vendor_name = $username;
            $business_name = $username . " Farms";
            $vendor_stmt = $conn->prepare("INSERT INTO vendors (user_id, vendor_name, email, business_name) VALUES (?, ?, ?, ?)");
            $vendor_stmt->bind_param("isss", $user_id, $vendor_name, $email, $business_name);
            if (!$vendor_stmt->execute()) {
                error_log("Vendor insert failed: " . $vendor_stmt->error);
            }
            $vendor_stmt->close();
        }
        $check_stmt->close();
    } else {
        $vendor_stmt = $conn->prepare("DELETE FROM vendors WHERE user_id = ?");
        $vendor_stmt->bind_param("i", $user_id);
        if (!$vendor_stmt->execute()) {
            error_log("Vendor delete failed: " . $vendor_stmt->error);
        }
        $vendor_stmt->close();
    }

    $stmt->close();
    // CHANGE: Added anchor to redirect
    header("Location: admin_dashboard.php?message=User updated successfully&type=success#user-management-section");
} else {
    $error = $conn->error;
    $stmt->close();
    // NEW: Log SQL error
    error_log("Edit user failed: " . $error);
    // CHANGE: Added anchor to redirect
    header("Location: admin_dashboard.php?message=Error updating user: " . urlencode($error) . "&type=danger#user-management-section");
}
?>