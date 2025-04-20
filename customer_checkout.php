<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: auth.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items from DB
$sql = "SELECT c.*, p.name, p.price FROM cart c 
        JOIN products p ON c.product_id = p.product_id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: customer_view_cart.php");
    exit();
}

// Calculate total and prepare order items
$total_price = 0;
$order_items = [];

while ($row = $result->fetch_assoc()) {
    $subtotal = $row['price'] * $row['quantity'];
    $total_price += $subtotal;
    $order_items[] = [
        'product_id' => $row['product_id'],
        'quantity' => $row['quantity']
    ];
}

// Insert into orders table
$status = 'Pending';
$order_date = date("Y-m-d H:i:s");
$stmt = $conn->prepare("INSERT INTO orders (customer_id, total_price, status, order_date) VALUES (?, ?, ?, ?)");
$stmt->bind_param("idss", $user_id, $total_price, $status, $order_date);
$stmt->execute();
$order_id = $stmt->insert_id;

// Insert into order_items
foreach ($order_items as $item) {
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $order_id, $item['product_id'], $item['quantity']);
    $stmt->execute();
}

// Clear cart
$stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Redirect to dummy payment gateway
header("Location: dummy_payment_gateway.php?order_id=" . $order_id);
exit();
?>
