<?php
session_start();
include 'db_connect.php';

// Fetch all products ordered by ID
$sql = "SELECT * FROM products ORDER BY product_id DESC";
$result = $conn->query($sql);

// Group products by category
$grouped_products = [];
if ($result->num_rows > 0) {
    while ($product = $result->fetch_assoc()) {
        $grouped_products[$product['category']][] = $product;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-image: url('products_background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .navbar {
            background-color: #155724 !important;
        }
        .navbar .nav-link {
            color: white !important;
        }
        .navbar .nav-link:hover {
            background-color: #1e7e34;
            border-radius: 5px;
        }
        .product-container {
            background: #28a745;
            color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }
        .product-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            background: white;
            color: black;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .product-body {
            flex-grow: 1;
        }
        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .spacer {
            margin-top: 50px;
            margin-bottom: 50px;
        }
        h3.category-heading {
            margin-top: 50px;
            color: #fff;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">AgriMarket</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="orders.php">Orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendors.php">Vendors</a></li>
                    <li class="nav-item"><a class="nav-link" href="reports.php">Reports</a></li>
                    <li class="nav-item"><a class="nav-link" href="settings.php">Settings</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container product-container">
        <h2 class="text-white">Product Management</h2>
        <p>Manage all your agricultural products in one place.</p>
        <a href="add_product.php" class="btn btn-light mb-3">Add New Product</a>

        <?php if (!empty($grouped_products)): ?>
            <?php foreach ($grouped_products as $category => $products): ?>
                <h3 class="category-heading">
                    <?php
                    // Assign emoji based on category
                    $icon = match ($category) {
                        'Fresh Produce' => '🌽',
                        'Fruits' => '🍍',
                        'Herbs & Spices' => '🌿',
                        'Fertilizers & Soil Enhancers' => '🌱',
                        'Seeds & Saplings' => '🌾',
                        default => '🛒',
                    };
                    echo "$icon $category";
                    ?>
                </h3>

                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php foreach ($products as $product): ?>
                        <div class="col">
                            <div class="card product-card">
                                <img src="uploads/<?php echo htmlspecialchars($product['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <div class="card-body d-flex flex-column">
                                    <div class="product-body">
                                        <h5 class="card-title text-success"><?php echo htmlspecialchars($product['name']); ?></h5>
                                        <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                                        <p class="text-success fw-bold">RM <?php echo number_format($product['price'], 2); ?></p>
                                    </div>
                                    <div class="btn-group">
                                        <a href="edit_product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-primary">Edit</a>
                                        <a href="delete_product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-white">No products found.</p>
        <?php endif; ?>
    </div>

    <div class="spacer"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>