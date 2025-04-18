<?php
include 'db_connect.php';

$searchTerm = $_GET['query'] ?? '';

if ($searchTerm) {
    $searchTerm = $conn->real_escape_string($searchTerm);
    $sql = "SELECT id, name, description, category, price FROM products WHERE name LIKE '%$searchTerm%' OR description LIKE '%$searchTerm%'";
    $result = $conn->query($sql);

    $products = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    echo json_encode($products);
} else {
    echo json_encode([]);
}

$conn->close();
?>
