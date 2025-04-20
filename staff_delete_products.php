<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}

$username = $_SESSION['username'];
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    $_SESSION['message'] = "Invalid product ID.";
    header("Location: staff_products.php");
    exit();
}

// Check if the product exists before deleting
$query = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    $_SESSION['message'] = "Product not found.";
    header("Location: staff_products.php");
    exit();
}

// Proceed to delete the product
$deleteQuery = "DELETE FROM products WHERE product_id = ?";
$stmt = $conn->prepare($deleteQuery);
$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {
    $_SESSION['message'] = "Product deleted successfully.";
    header("Location: staff_products.php");
} else {
    $_SESSION['message'] = "Error deleting product.";
    header("Location: staff_products.php");
}

exit();