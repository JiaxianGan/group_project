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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $updateQuery = "UPDATE products SET name = ?, category = ?, price = ?, stock_quantity = ? WHERE product_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssdii", $name, $category, $price, $stock_quantity, $product_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Product updated successfully.";
        header("Location: staff_products.php");
        exit();
    } else {
        $_SESSION['message'] = "Error updating product.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product - AgriMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Edit Product</h3>
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <input type="text" class="form-control" id="category" name="category" value="<?php echo htmlspecialchars($product['category']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price (RM)</label>
            <input type="number" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="stock_quantity" class="form-label">Stock Quantity</label>
            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" value="<?php echo htmlspecialchars($product['stock_quantity']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="staff_products.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>