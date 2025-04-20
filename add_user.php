<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // CHANGE: Changed to redirect instead of JSON response
    header("Location: admin_dashboard.php?message=Unauthorized access&type=danger");
    exit();
}

// CHANGE: Switched from JSON to $_POST
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$phone = trim($_POST['phone'] ?? '');
$role = trim($_POST['role'] ?? '');

// NEW: Server-side validation
if (empty($username) || empty($email) || empty($password) || empty($role)) {
    header("Location: admin_dashboard.php?message=All fields except phone are required&type=danger");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: admin_dashboard.php?message=Invalid email format&type=danger");
    exit();
}

if (strlen($password) < 8) {
    header("Location: admin_dashboard.php?message=Password must be at least 8 characters&type=danger");
    exit();
}

// CHANGE: Simplified duplicate check with prepared statement
$stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stmt->close();
    header("Location: admin_dashboard.php?message=Username or email already exists&type=danger");
    exit();
}
$stmt->close();

// CHANGE: Simplified insert with prepared statement
$password_hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, phone, role) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $username, $email, $password_hash, $phone, $role);

if ($stmt->execute()) {
    $user_id = $stmt->insert_id;

    // CHANGE: Simplified vendor creation
    if ($role === 'vendor') {
        $vendor_name = $username;
        $business_name = $username . " Farms";
        $vendor_stmt = $conn->prepare("INSERT INTO vendors (user_id, vendor_name, email, business_name) VALUES (?, ?, ?, ?)");
        $vendor_stmt->bind_param("isss", $user_id, $vendor_name, $email, $business_name);
        $vendor_stmt->execute();
        $vendor_stmt->close();
    }

    $stmt->close();
    // CHANGE: Redirect with success message
    header("Location: admin_dashboard.php?message=User created successfully&type=success");
} else {
    $stmt->close();
    // CHANGE: Redirect with error message
    header("Location: admin_dashboard.php?message=Error creating user: " . urlencode($conn->error) . "&type=danger");
}
?>