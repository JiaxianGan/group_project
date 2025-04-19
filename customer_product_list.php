<?php
session_start();
include 'db_connect.php';

// Ensure the user is a customer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: auth.php");
    exit();
}

// Get selected category filter from GET
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

// Get distinct categories
$category_stmt = $conn->prepare("SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != ''");
$category_stmt->execute();
$category_result = $category_stmt->get_result();

// Prepare product fetch query
if (!empty($category_filter)) {
    $product_stmt = $conn->prepare("SELECT * FROM products WHERE category = ? ORDER BY product_id DESC");
    $product_stmt->bind_param("s", $category_filter);
} else {
    $product_stmt = $conn->prepare("SELECT * FROM products ORDER BY product_id DESC");
}
$product_stmt->execute();
$product_result = $product_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products | AgriMarket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; padding: 20px; }
        .navbar { background-color: #28a745; }
        .navbar .nav-link { color: white !important; }
        .navbar .nav-link:hover { background-color: #218838; border-radius: 5px; }
        .product-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .product-item {
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .product-item img {
            max-width: 100%;
            height: 200px;
            object-fit: contain;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .product-item h5 { color: #28a745; }
        .btn-submit {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .btn-submit:hover { background-color: #218838; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#">AgriMarket</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="customer_dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="customer_product_list.php">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="orders.php">Orders</a></li>
                <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2>Available Products</h2>

    <!-- Category Filter -->
    <form method="GET" class="mb-4">
        <label for="category" class="me-2">Filter by Category:</label>
        <select name="category" id="category" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
            <option value="">All Categories</option>
            <?php while ($cat = $category_result->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($cat['category']); ?>" <?= ($category_filter == $cat['category']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['category']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

    <!-- Product List -->
    <div class="product-list">
        <?php if ($product_result && $product_result->num_rows > 0): ?>
            <?php while ($product = $product_result->fetch_assoc()): ?>
                <div class="product-item">
                    <?php
                    $image_path = !empty($product['image_url']) && file_exists("product_images/" . $product['image_url'])
                        ? "product_images/" . htmlspecialchars($product['image_url'])
                        : "product_images/default-placeholder.png";
                    ?>
                    <img src="<?= $image_path ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                    <h5><?= htmlspecialchars($product['name']); ?></h5>
                    <p><?= !empty($product['description']) ? htmlspecialchars($product['description']) : 'No description available'; ?></p>
                    <p><strong>Price:</strong> RM <?= number_format($product['price'], 2); ?></p>
                    <p><strong>Stock:</strong> <?= htmlspecialchars($product['stock_quantity']); ?></p>
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']); ?>">
                        <input type="number" name="quantity" min="1" max="<?= htmlspecialchars($product['stock_quantity']); ?>" value="1" class="form-control mb-2" required>
                        <button type="submit" name="add_to_cart" class="btn-submit">Add to Cart</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products available.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
