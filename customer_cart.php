<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: auth.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity <= 0) {
        $_SESSION['error'] = 'Invalid quantity selected.';
        header("Location: customer_product_list.php?msg=invalid_quantity");
        exit();
    }

    // Check if item already in cart
    $check_sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update quantity if item already in cart
        $update_sql = "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("iii", $quantity, $user_id, $product_id);
    } else {
        // Insert new item to cart
        $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    }

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Item added to cart successfully!';
        header("Location: customer_product_list.php?msg=added");
        exit();
    } else {
        $_SESSION['error'] = 'Error adding item to cart. Please try again.';
        header("Location: customer_product_list.php?msg=error");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    // If accessed directly without POST
    header("Location: customer_product_list.php");
    exit();
}
?>
