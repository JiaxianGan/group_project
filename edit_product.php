<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$product_id = $_GET['id'];

// Fetch current product data
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "Product not found!";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Handle image upload if a new one is provided
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . '_' . basename($_FILES["image"]["name"]);
        $target_dir = "uploads/";
        $target_file = $target_dir . $image_name;

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

        if (in_array($imageFileType, $allowed_types)) {
            // Delete old image if exists
            if (!empty($product['image_url']) && file_exists("uploads/" . $product['image_url'])) {
                unlink("uploads/" . $product['image_url']);
            }

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, image_url=? WHERE product_id=?");
                $stmt->bind_param("ssdsi", $name, $description, $price, $image_name, $product_id);
                $stmt->execute();
            } else {
                echo "Failed to upload new image.";
                exit();
            }
        } else {
            echo "Invalid image format.";
            exit();
        }
    } else {
        // No new image
        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=? WHERE product_id=?");
        $stmt->bind_param("ssdi", $name, $description, $price, $product_id);
        $stmt->execute();
    }

    header("Location: products.php?msg=updated");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('products_background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .container {
            margin-top: 50px;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-bottom: 25px;
            font-weight: 600;
            color: #155724;
        }
        .btn-back {
            margin-bottom: 20px;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        img.preview-img {
            width: 150px;
            height: auto;
            margin-bottom: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Product</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Product Name:</label>
            <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($product['name']) ?>">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description:</label>
            <textarea name="description" class="form-control" required rows="4"><?= htmlspecialchars($product['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price (RM):</label>
            <input type="number" step="0.01" name="price" class="form-control" required value="<?= htmlspecialchars($product['price']) ?>">
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Update Image (optional):</label><br>
            <?php if (!empty($product['image_url'])): ?>
                <img src="uploads/<?= htmlspecialchars($product['image_url']) ?>" class="preview-img"><br>
            <?php endif; ?>
            <input type="file" name="image" accept="image/*" class="form-control mt-2">
        </div>
        <button type="submit" class="btn btn-success">Update Product</button>
        <a href="products.php" class="btn btn-secondary btn-back">Back to Products</a>
    </form>
</div>
</body>
</html>