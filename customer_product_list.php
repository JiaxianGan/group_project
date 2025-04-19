<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: auth.php");
    exit();
}

$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

$category_stmt = $conn->prepare("SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != ''");
$category_stmt->execute();
$category_result = $category_stmt->get_result();

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-bottom: 80px;
        }
        .navbar {
            background-color: #28a745;
        }
        .navbar .nav-link {
            color: white !important;
        }
        .navbar .nav-link:hover {
            background-color: #218838;
            border-radius: 5px;
        }
        .product-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .product-item {
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            min-height: 450px;
        }
        .image-container {
            width: 100%;
            height: 140px;
            overflow: hidden;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .product-item h5 {
            color: #28a745;
            font-size: 1.1rem;
            margin-bottom: 8px;
            min-height: 2.6em;
        }
        .product-item p {
            font-size: 0.9rem;
            margin-bottom: 8px;
        }
        .product-item form {
            margin-top: auto;
        }
        .btn-submit {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
        }
        .btn-submit:hover {
            background-color: #218838;
        }
        .bottom-buttons {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #fff;
            padding: 10px 20px;
            box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            z-index: 999;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#"><i class="bi bi-shop"></i> AgriMarket</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-3">
                <li class="nav-item">
                    <a class="nav-link" href="customer_dashboard.php"><i class="bi bi-house-door"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="customer_product_list.php"><i class="bi bi-box-seam"></i> Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="customer_add_to_cart.php"><i class="bi bi-cart4"></i> My Cart</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="customer_order_history.php"><i class="bi bi-bag-check"></i> My Orders</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-gear"></i> Settings
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="customer_view_profile.php"><i class="bi bi-person"></i> View Profile</a></li>
                        <li><a class="dropdown-item" href="customer_edit_profile.php"><i class="bi bi-pencil-square"></i> Edit Profile</a></li>
                        <li><a class="dropdown-item" href="change_password.php"><i class="bi bi-lock"></i> Change Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2>Available Products</h2>

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

    <div class="product-list">
        <?php if ($product_result && $product_result->num_rows > 0): ?>
            <?php while ($product = $product_result->fetch_assoc()): ?>
                <div class="product-item">
                    <div class="image-container">
                        <?php
                        $image_path = !empty($product['image_url']) && file_exists("product_images/" . $product['image_url'])
                            ? "product_images/" . htmlspecialchars($product['image_url'])
                            : "product_images/default-placeholder.png";
                        ?>
                        <img src="<?= $image_path ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                    </div>
                    <h5><?= htmlspecialchars($product['name']); ?></h5>
                    <p><?= !empty($product['description']) ? htmlspecialchars($product['description']) : 'No description available'; ?></p>
                    <p><strong>Price:</strong> RM <?= number_format($product['price'], 2); ?></p>
                    <p><strong>Stock:</strong> <?= htmlspecialchars($product['stock_quantity']); ?></p>
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']); ?>">
                        <input type="number" name="quantity" min="1" max="<?= htmlspecialchars($product['stock_quantity']); ?>" value="1" class="form-control mb-2" required>
                        <button type="submit" name="add_to_cart" class="btn-submit w-100"><i class="bi bi-cart-plus"></i> Add to Cart</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products available.</p>
        <?php endif; ?>
    </div>
</div>

<div class="bottom-buttons">
    <a href="customer_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle"></i> Back to Dashboard</a>
    <a href="payment_page.php" class="btn btn-success"><i class="bi bi-credit-card-2-front-fill"></i> Checkout</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
