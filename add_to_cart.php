<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $quantity = max(1, intval($_POST['quantity'])); // Minimum quantity 1

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    $_SESSION['message'] = "Product successfully added to cart!";
    header("Location: product_list.php");
    exit();
} else {
    $_SESSION['message'] = "Invalid action!";
    header("Location: product_list.php");
    exit();
}
