<?php
session_start();
include 'db_connect.php';

// Check if user is logged in and is a customer
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
    header("Location: auth.php");
    exit();
}

$cart = $_SESSION['cart'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard - AgriMarket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-image: url('dashboard_background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .navbar {
            background-color: #155724 !important;
        }
        .navbar .nav-link, .navbar .navbar-brand {
            color: white !important;
        }
        .navbar .nav-link:hover {
            background-color: #1e7e34;
            border-radius: 5px;
        }
        .dashboard-container {
            padding: 40px 20px;
        }
        .hero {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .hero-text {
            flex: 1;
            max-width: 50%;
        }
        .hero-text h1 {
            font-size: 2.5rem;
            color: #155724;
            font-weight: bold;
        }
        .hero-text .highlight {
            color: #ffcc00;
            font-weight: bold;
        }
        .hero-buttons a {
            margin-right: 10px;
        }
        .shop-btn {
            background: #ffcc00;
            color: #333;
            font-weight: bold;
        }
        .shop-btn:hover {
            background: #e6b800;
        }
        .info-btn {
            background: #155724;
            color: white;
            border: 2px solid white;
        }
        .info-btn:hover {
            background: white;
            color: #155724;
        }
        .hero-image {
            flex: 1;
            max-width: 50%;
            text-align: center;
        }
        .hero-image img {
            width: 100%;
            max-width: 450px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        @media (max-width: 768px) {
            .hero {
                flex-direction: column;
                text-align: center;
            }
            .hero-text, .hero-image {
                max-width: 100%;
            }
        }
        .search-container input {
            border: none;
            padding: 5px;
            outline: none;
            width: 180px;
        }
        .search-container button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            color: #155724;
        }
        .section-title {
            margin-top: 40px;
            margin-bottom: 20px;
            font-weight: bold;
            color: #155724;
        }
    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#"><i class="bi bi-shop"></i> AgriMarket</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="ms-auto d-flex align-items-center">
                <form action="product_list.php" method="get" class="d-flex search-container me-3 bg-white rounded">
                    <input type="text" name="search" placeholder="Search products..." required>
                    <button type="submit"><i class="bi bi-search"></i></button>
                </form>

                <a href="cart.php" class="text-white position-relative ms-3" title="Shopping Cart">
                    <i class="bi bi-cart3 fs-4"></i>
                    <?php if (!empty($cart)): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= count($cart); ?>
                        </span>
                    <?php endif; ?>
                </a>
            </div>

            <ul class="navbar-nav ms-3">
                <li class="nav-item"><a class="nav-link" href="customer_dashboard.php"><i class="bi bi-house-door"></i> Home</a></li>
                <li class="nav-item"><a class="nav-link" href="product_list.php"><i class="bi bi-box-seam"></i> Products</a></li>
                <li class="nav-item"><a class="nav-link" href="order_history.php"><i class="bi bi-bag-check"></i> My Orders</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-gear"></i> Settings
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="customer_view_profile.php"><i class="bi bi-person"></i> View Profile</a></li>
                        <li><a class="dropdown-item" href="customer_edit_profile.php"><i class="bi bi-pencil-square"></i> Edit Profile</a></li>
                        <li><a class="dropdown-item" href="change_password.php"><i class="bi bi-lock"></i> Change Password</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Dashboard -->
<div class="container dashboard-container">
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-text">
            <h2>Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</h2>
            <p>Discover the best agricultural products at your fingertips.</p>
            <h1>Fresh from the <span class="highlight">Farm</span> to Your Home</h1>
            <p>Support local farmers and enjoy fresh, organic produce today.</p>
            <div class="hero-buttons mt-3">
                <a href="product_list.php" class="btn shop-btn"><i class="bi bi-cart4"></i> Shop Now</a>
                <a href="about.php" class="btn info-btn"><i class="bi bi-info-circle"></i> Learn More</a>
            </div>
        </div>
        <div class="hero-image mt-4 mt-md-0">
            <img src="farm.jpg" alt="Farm Image">
        </div>
    </section>

    <!-- Featured Products -->
    <section class="mt-5">
        <h3 class="section-title"><i class="bi bi-star-fill text-warning"></i> Featured Products</h3>
        <div class="row">
            <?php
            $featured = $conn->query("SELECT * FROM products ORDER BY RAND() LIMIT 3");
            while ($prod = $featured->fetch_assoc()):
            ?>
            <div class="col-md-4 mb-3">
                <div class="card h-100 shadow-sm">
                    <img src="<?= $prod['image_path'] ?? 'default.jpg'; ?>" class="card-img-top" alt="<?= $prod['product_name']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $prod['product_name']; ?></h5>
                        <p class="card-text">RM <?= number_format($prod['price'], 2); ?></p>
                        <a href="product_details.php?id=<?= $prod['id']; ?>" class="btn btn-success btn-sm">View</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- Cart Preview -->
    <section>
        <h3 class="section-title"><i class="bi bi-cart4"></i> Your Cart</h3>
        <?php if (empty($cart)): ?>
            <div class="alert alert-warning">Your cart is empty. Start shopping now!</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover bg-white shadow-sm">
                    <thead class="table-success">
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price (RM)</th>
                            <th>Total (RM)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        foreach ($cart as $product_id => $qty):
                            $res = $conn->query("SELECT * FROM products WHERE id = $product_id");
                            $product = $res->fetch_assoc();
                            $subtotal = $product['price'] * $qty;
                            $total += $subtotal;
                        ?>
                        <tr>
                            <td><?= $product['product_name']; ?></td>
                            <td><?= $qty; ?></td>
                            <td><?= number_format($product['price'], 2); ?></td>
                            <td><?= number_format($subtotal, 2); ?></td>
                            <td><a href="remove_from_cart.php?id=<?= $product_id; ?>" class="btn btn-sm btn-danger">Remove</a></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Grand Total</td>
                            <td colspan="2" class="fw-bold">RM <?= number_format($total, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <a href="cart.php" class="btn btn-primary me-2"><i class="bi bi-basket"></i> Go to Cart</a>
            <a href="checkout.php" class="btn btn-success"><i class="bi bi-credit-card"></i> Proceed to Checkout</a>
        <?php endif; ?>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
