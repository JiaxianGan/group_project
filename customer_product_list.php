<?php
session_start();
include 'db_connect.php';

// Ensure only customers can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: auth.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

// Get distinct categories
$category_sql = "SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != ''";
$categories = $conn->query($category_sql);

// Fetch products
$product_sql = "SELECT * FROM products";
if (!empty($category_filter)) {
    $product_sql .= " WHERE category = '" . $conn->real_escape_string($category_filter) . "'";
}
$product_sql .= " ORDER BY stock_quantity ASC";
$products = $conn->query($product_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products | AgriMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
            padding: 20px;
        }
        h2 {
            color: #155724;
            text-align: center;
            margin-bottom: 30px;
        }
        .product-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        .product-item {
            background: white;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            text-align: center;
        }
        .product-item img {
            max-width: 100%;
            height: 200px;
            object-fit: contain;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .btn-submit {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .btn-submit:hover {
            background-color: #218838;
        }
        .btn-back {
            margin-top: 40px;
            display: inline-block;
            background-color: #155724;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn-back:hover {
            background-color: #0f3d21;
        }
    </style>
</head>
<body>
    <h2>Available Products</h2>

    <!-- Filter Form -->
    <form method="GET" class="mb-4 text-center">
        <label class="me-2">Filter by Category:</label>
        <select name="category" onchange="this.form.submit()" class="form-select d-inline-block w-auto">
            <option value="">All Categories</option>
            <?php while ($cat = $categories->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($cat['category']); ?>" <?= ($category_filter == $cat['category']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['category']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

    <!-- Product Listing -->
    <div class="product-list">
        <?php if ($products && $products->num_rows > 0): ?>
            <?php while ($row = $products->fetch_assoc()): ?>
                <div class="product-item">
                <img src="<?= 'http://localhost/agrimarketsolutions/' . htmlspecialchars($row['image_url']); ?>" alt="<?= htmlspecialchars($row['name']); ?>" class="img-fluid" style="height: 200px;">

                    <h5><?= htmlspecialchars($row['name']); ?></h5>
                    <p><?= htmlspecialchars($row['description']); ?></p>
                    <p><strong>Packaging:</strong> <?= htmlspecialchars($row['packaging']); ?></p>
                    <p><strong>Stock:</strong> <?= htmlspecialchars($row['stock_quantity']); ?></p>
                    <p><strong>Price:</strong> RM <?= number_format($row['price'], 2); ?> / <?= htmlspecialchars($row['packaging']); ?></p>

                    <!-- Add to Cart -->
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($row['product_id']); ?>">
                        <div class="mb-2">
                            <label for="quantity_<?= $row['product_id']; ?>">Quantity:</label>
                            <input type="number" name="quantity" id="quantity_<?= $row['product_id']; ?>" min="1" max="<?= $row['stock_quantity']; ?>" value="1" class="form-control" required>
                        </div>
                        <button type="submit" name="add_to_cart" class="btn-submit">Add to Cart</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No products found in this category.</p>
        <?php endif; ?>
    </div>

    <div class="text-center">
        <a href="customer_dashboard.php" class="btn-back mt-5">← Back to Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
