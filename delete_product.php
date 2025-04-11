<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT product_image FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product && file_exists("uploads/" . $product['product_image'])) {
        unlink("uploads/" . $product['product_image']);
    }

    $del = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $del->bind_param("i", $id);
    $del->execute();
}

header("Location: products.php?msg=deleted");
exit();
?>