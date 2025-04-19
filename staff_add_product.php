<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}

$successMsg = $errorMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $vendorId = isset($_POST['vendor_id']) ? $_POST['vendor_id'] : '';
    $category = isset($_POST['category']) ? $_POST['category'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $price = isset($_POST['price']) ? $_POST['price'] : 0;
    $stockQuantity = isset($_POST['stock_quantity']) ? $_POST['stock_quantity'] : 0;
    $packaging = isset($_POST['packaging']) ? $_POST['packaging'] : '';
    $imagePath = "";

    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['product_image']['tmp_name'];
        $fileName = $_FILES['product_image']['name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = uniqid() . '.' . $fileExtension;
        $uploadDir = 'uploads/';
        $dest_path = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $imagePath = $dest_path;
        } else {
            $errorMsg = "Error uploading the image.";
        }
    }

    if (empty($errorMsg)) {
        $sql = "INSERT INTO products (vendor_id, name, category, description, price, stock_quantity, packaging, image_url, product_image)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("isssdisss", $vendorId, $name, $category, $description, $price, $stockQuantity, $packaging, $imagePath, $imagePath);

            if ($stmt->execute()) {
                $successMsg = "Product added successfully!";
            } else {
                $errorMsg = "Execution failed: " . $stmt->error;
            }
        } else {
            $errorMsg = "Prepare failed: " . $conn->error;
        }
    }
}

$vendors = $conn->query("SELECT vendor_id, vendor_name FROM vendors");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product - AgriMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('vendors_background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .container {
            margin-top: 80px;
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            max-width: 800px;
        }

        .btn-success {
            background-color: #155724;
            border: none;
        }

        .btn-success:hover {
            background-color: #1e7e34;
        }
    </style>
</head>
<body>

<div class="container shadow">
    <h2 class="mb-4 fw-bold text-success">Add New Product</h2>

    <?php if ($successMsg): ?>
        <div class="alert alert-success"><?php echo $successMsg; ?></div>
    <?php elseif ($errorMsg): ?>
        <div class="alert alert-danger"><?php echo $errorMsg; ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Vendor</label>
            <select name="vendor_id" class="form-select" required>
                <option value="">-- Select Vendor --</option>
                <?php
                if ($vendors && $vendors->num_rows > 0) {
                    while ($vendor = $vendors->fetch_assoc()) {
                        echo "<option value='{$vendor['vendor_id']}'>{$vendor['vendor_name']}</option>";
                    }
                } else {
                    echo "<option disabled>No vendors found</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-select" required>
                <option value="">-- Select Category --</option>
                <option value="livestock">Livestock</option>
                <option value="crops">Crops</option>
                <option value="edible_forestry">Edible Forestry</option>
                <option value="dairy">Dairy</option>
                <option value="fish_farming">Fish Farming</option>
                <option value="miscellaneous">Miscellaneous</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Price (RM)</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Stock Quantity</label>
            <input type="number" name="stock_quantity" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Packaging</label>
            <input type="text" name="packaging" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Upload Image</label>
            <input type="file" name="product_image" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-success">Add Product</button>
        <a href="staff_vendors.php" class="btn btn-secondary">Back</a>
    </form>
</div>

</body>
</html>