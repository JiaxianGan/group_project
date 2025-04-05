<?php
include 'db_connect.php';

if (isset($_POST['order_id'], $_POST['customer_name'], $_POST['total_price'], $_POST['status'])) {
    $order_id = $_POST['order_id'];
    $customer_name = $_POST['customer_name'];
    $total_price = $_POST['total_price'];
    $status = $_POST['status'];

    $query = "UPDATE orders SET customer_name = ?, total_price = ?, status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sdsi", $customer_name, $total_price, $status, $order_id);

    if ($stmt->execute()) {
        header("Location: orders.php");
        exit();
    } else {
        echo "Error updating order!";
    }
} else {
    echo "Missing required fields!";
}
?>
