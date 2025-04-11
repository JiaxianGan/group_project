<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['product_name'];
    $description = $_POST['product_description'];
    $price = $_POST['product_price'];

    $image_name = '';
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true);
        }

        $tmp_name = $_FILES['product_image']['tmp_name'];
        $original_name = basename($_FILES['product_image']['name']);
        $image_name = time() . '_' . $original_name;
        $destination = 'uploads/' . $image_name;

        if (!move_uploaded_file($tmp_name, $destination)) {
            $message = "Failed to upload image.";
        }
    }

    if (!$message) {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_url) VALUES (?, ?, ?, ?)");

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("ssds", $name, $description, $price, $image_name);
        $stmt->execute();

        header("Location: products.php?msg=added");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('products_background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .form-container {
            background: white;
            padding: 40px;
            margin-top: 50px;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-container">
        <h3>Add New Product</h3>
        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="product_name" class="form-label">Product Name:</label>
                <input type="text" name="product_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="product_description" class="form-label">Description:</label>
                <textarea name="product_description" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="product_price" class="form-label">Price (RM):</label>
                <input type="number" step="0.01" name="product_price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="product_image" class="form-label">Product Image:</label>
                <input type="file" name="product_image" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-success">Add Product</button>
            <a href="products.php" class="btn btn-secondary btn-back">Back to Products</a>
        </form>
    </div>
</div>
</body>
</html>