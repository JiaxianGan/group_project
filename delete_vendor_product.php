<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}

// Get the product ID from the request
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id > 0) {
    // Prepare a statement to delete the product
    $query = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("i", $product_id);
        if ($stmt->execute()) {
            // Product successfully deleted
            $_SESSION['message'] = "Product deleted successfully.";
        } else {
            // Error occurred during deletion
            $_SESSION['message'] = "Error deleting product.";
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Error preparing statement.";
    }
} else {
    $_SESSION['message'] = "Invalid product ID.";
}

// Redirect back to the manage products page
header("Location: vendor_product.php");
exit();
?>
