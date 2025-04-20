<?php
session_start();
include 'db_connect.php';  // DB connection

// Ensure the cart exists in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Ensure it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    // Update quantities
    if (isset($_POST['quantities'])) {
        foreach ($_POST['quantities'] as $product_id => $quantity) {
            $product_id = intval($product_id);
            $quantity = intval($quantity);

            if ($quantity > 0) {
                $product_query = $conn->prepare("SELECT stock_quantity FROM products WHERE product_id = ?");
                $product_query->bind_param("i", $product_id);
                $product_query->execute();
                $product_result = $product_query->get_result();

                if ($product_result->num_rows > 0) {
                    $product = $product_result->fetch_assoc();
                    if ($quantity <= $product['stock_quantity']) {
                        $_SESSION['cart'][$product_id] = $quantity;
                    } else {
                        $errors[] = "Requested quantity exceeds available stock for product ID $product_id.";
                    }
                } else {
                    $errors[] = "Invalid product ID $product_id.";
                }
            } else {
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }

    // Delete items
    if (isset($_POST['delete'])) {
        foreach ($_POST['delete'] as $product_id => $val) {
            $product_id = intval($product_id);
            unset($_SESSION['cart'][$product_id]);
        }
    }

    // Save error messages and redirect
    if (!empty($errors)) {
        $_SESSION['update_cart_errors'] = $errors;
    }

    header("Location: customer_add_to_cart.php");
    exit();
} else {
    header("Location: customer_product_list.php");
    exit();
}
?>
