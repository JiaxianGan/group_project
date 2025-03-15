<?php
session_start();
include 'db_connect.php';

$products = [
    ["name" => "Durian Musang King", "description" => "Rich, creamy texture with a bittersweet flavor.", "price" => "68.00", "image_url" => "https://tse1.mm.bing.net/th?id=OIP.W05qsel0P3VG2cqjWIiH7AHaE8&pid=Api"],
    ["name" => "Cameron Highland Strawberries", "description" => "Juicy and sweet strawberries from Malaysia's highlands.", "price" => "25.00", "image_url" => "https://tse3.mm.bing.net/th?id=OIP.vGqjYaj28cMWNd9DM53HeAHaGg&pid=Api"],
    ["name" => "Organic Bananas", "description" => "Delicious, naturally grown bananas with no chemicals.", "price" => "12.00", "image_url" => "https://tse2.mm.bing.net/th?id=OIP.ZV-B3MDmXuuKC1P7df1ESwHaJ4&pid=Api"],
    ["name" => "Red Chillies", "description" => "Bright, spicy red chillies perfect for any dish.", "price" => "15.00", "image_url" => "https://tse2.mm.bing.net/th?id=OIP.krjhVVPi3w9vw7GkRmp8RQHaE8&pid=Api"],
    ["name" => "Pak Choy", "description" => "Fresh, vitamin-rich leafy green for stir-fries and soups.", "price" => "8.00", "image_url" => "pak_choy.jpg"],
    ["name" => "Sweet Corn", "description" => "Juicy and fresh, perfect for boiling or grilling.", "price" => "10.00", "image_url" => "sweet_corn.jpg"],
    ["name" => "Organic Fertilizer", "description" => "Eco-friendly fertilizer for healthy plant growth.", "price" => "30.00", "image_url" => "organic_fertilizer.jpg"],
    ["name" => "Hybrid Papaya", "description" => "Sweet, long-lasting papaya variety.", "price" => "18.00", "image_url" => "hybrid_papaya.jpg"],
    ["name" => "Fresh Cucumbers", "description" => "Crisp and hydrating cucumbers, great for salads.", "price" => "9.00", "image_url" => "cucumbers.jpg"],
    ["name" => "Dragon Fruit", "description" => "Nutrient-rich fruit with a vibrant appearance.", "price" => "22.00", "image_url" => "dragon_fruit.jpg"]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    
    <div class="container product-container">
        <h2 class="text-white">Product Management</h2>
        <p>Manage all your agricultural products in one place.</p>
        <a href="add_product.php" class="btn btn-light mb-3">Add New Product</a>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($products as $product) { ?>
                <div class="col">
                    <div class="card product-card">
                        <img src="<?php echo $product['image_url']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                        <div class="card-body d-flex flex-column">
                            <div class="product-body">
                                <h5 class="card-title text-success"><?php echo $product['name']; ?></h5>
                                <p class="card-text"><?php echo $product['description']; ?></p>
                                <p class="text-success fw-bold">RM <?php echo $product['price']; ?></p>
                            </div>
                            <div class="btn-group">
                                <a href="edit_product.php?id=<?php echo $product['name']; ?>" class="btn btn-primary">Edit</a>
                                <a href="delete_product.php?id=<?php echo $product['name']; ?>" class="btn btn-danger">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="spacer"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>