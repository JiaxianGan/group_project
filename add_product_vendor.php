<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}
$vendor_id = isset($_SESSION['vendor_id']) ? $_SESSION['vendor_id'] : 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    // Ensure vendor_id is present before proceeding
    if ($vendor_id == null) {
        die("Error: Vendor ID not found in session.");
    }
    $name = $_POST['name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $packaging = $_POST['packaging'];
    
    

    $sql = "INSERT INTO products (product_id, vendor_id, name, category, description, price, stock_quantity, packaging) VALUES ('$product_id','$vendor_id', '$name','$category', '$description', '$price', '$stock_quantity','$packaging')";
    if (mysqli_query($conn, $sql)) {
        header("Location: vendor_product.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
$product_query = "SELECT * FROM products WHERE vendor_id = '$vendor_id'";
$result = mysqli_query($conn, $product_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Add New Product</h1>
    <form method="post">
        <div class="mb-3">
            <label for="product_id" class="form-label">Product ID</label>
            <input type="number" class="form-control" id="product_id" name="product_id" required>
        </div>
        <div class="mb-3">
            <label for="vendor_id" class="form-label">Vendor ID</label>
            <input type="number" class="form-control" id="vendor_id" name="vendor_id" required>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Product Category</label>
            <input type="text" class="form-control" id="category" name="category" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Product Description</label>
            <input type="text" class="form-control" id="description" name="description" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" required>
        </div>
        <div class="mb-3">
            <label for="stock_quantity" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" required>
        </div>
        <div class="mb-3">
            <label for="packaging" class="form-label">Packaging</label>
            <input type="text" class="form-control" id="packaging" name="packaging" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Product</button>
        <a href="vendor_product.php" class="btn btn-secondary">Back</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
