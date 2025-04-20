<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}

$product_id = isset($_SESSION['product_id']) ? $_SESSION['product_id'] : 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $vendor_id = $_POST['vendor_id']; // Assuming you have vendor_id in session
    $name = $_POST['name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $packaging = $_POST['packaging'];

    $sql = "UPDATE products SET  vendor_id = '$vendor_id', name = '$name', category = '$category', description = '$description', price = '$price', stock_quantity = '$stock_quantity', packaging = '$packaging' WHERE product_id='$product_id'";
    if (mysqli_query($conn, $sql)) {
        header("Location: vendor_product.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

$sql = "SELECT * FROM products WHERE product_id='$product_id'";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Edit Product</h1>
    <form method="post">
     
    <div class="mb-3">
        <label for="product_id" class="form-label">Product ID</label>
        <input type="text" class="form-control" id="product_id" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>" required>
    </div>
    
    <div class="mb-3">
        <label for="vendor_id" class="form-label">Vendor ID</label>
        <input type="text" class="form-control" id="vendor_id" name="vendor_id" value="<?php echo htmlspecialchars($product['vendor_id']); ?>" required>
    </div>
  
    <div class="mb-3">
        <label for="name" class="form-label">Product Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="category" class="form-label">Product Category</label>
        <input type="text" class="form-control" id="category" name="category" value="<?php echo htmlspecialchars($product['category']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Product Description</label>
        <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Price</label>
        <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="stock_quantity" class="form-label">Stock</label>
        <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" value="<?php echo htmlspecialchars($product['stock_quantity']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="packaging" class="form-label">Packaging</label>
        <input type="text" class="form-control" id="packaging" name="packaging" value="<?php echo htmlspecialchars($product['packaging']); ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update Product</button>
    <a href="vendor_product.php" class="btn btn-secondary">Back</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
