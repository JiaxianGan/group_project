<?php
session_start();
include 'db_connect.php';

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if vendor_id is set
if (isset($_GET['id'])) {
    $vendor_id = $_GET['id'];

    // Prepare and execute the delete statement
    $stmt = $conn->prepare("DELETE FROM vendors WHERE vendor_id = ?");
    $stmt->bind_param("i", $vendor_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Vendor deleted successfully.";
    } else {
        $_SESSION['message'] = "Failed to delete vendor: " . $conn->error;
    }
    header("Location: vendors.php");
    exit();
} else {
    $_SESSION['message'] = "No vendor ID provided.";
    header("Location: vendors.php");
    exit();
}
?>
