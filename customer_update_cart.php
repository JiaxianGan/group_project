<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update quantities
    if (isset($_POST['quantities'])) {
        foreach ($_POST['quantities'] as $product_id => $quantity) {
            $product_id = intval($product_id);
            $quantity = intval($quantity);

            if ($quantity > 0) {
                $_SESSION['cart'][$product_id] = $quantity;
            }
        }
    }

    // Delete individual item
    if (isset($_POST['delete'])) {
        foreach ($_POST['delete'] as $product_id => $val) {
            $product_id = intval($product_id);
            unset($_SESSION['cart'][$product_id]);
        }
    }

    // Redirect back to cart
    header("Location: customer_add_to_cart.php");
    exit();
} else {
    // Invalid access
    header("Location: customer_product_list.php");
    exit();
}
?>
