<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}

if (isset($_GET['id'])) {
    $staff_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM staff WHERE staff_id=?");
    $stmt->bind_param("i", $staff_id);
    
    if ($stmt->execute()) {
        header("Location: vendor_staff.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    header("Location: vendor_staff.php");
    exit();
}
