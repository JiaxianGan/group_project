<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - AgriMarket</title>
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
            padding: 40px;
        }
        .hero {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
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
        .hero-buttons {
            margin-top: 20px;
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
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
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
        .search-container {
            display: flex;
            align-items: center;
            background: white;
            padding: 5px 10px;
            border-radius: 5px;
            margin-right: 20px;
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
        .icons span {
            font-size: 1.3rem;
            color: white;
            margin-left: 15px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="bi bi-shop"></i> AgriMarket</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="ms-auto d-flex align-items-center">
                    <div class="search-container me-3">
                        <input type="text" placeholder="Search Products...">
                        <button><i class="bi bi-search"></i></button>
                    </div>
                    <span><i class="bi bi-person-circle"></i></span>
                    <span><i class="bi bi-cart3"></i></span>
                </div>

                <ul class="navbar-nav ms-3">
                    <li class="nav-item"><a class="nav-link" href="customer_dashboard.php"><i class="bi bi-house-door"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php"><i class="bi bi-box-seam"></i> Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="customer_orders.php"><i class="bi bi-bag-check"></i> My Orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="customer_profile.php"><i class="bi bi-gear"></i> Settings</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="container dashboard-container">
        <section class="hero">
            <div class="hero-text">
                <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
                <p>Discover the best agricultural products at your fingertips.</p>
                <h1>Fresh from the <span class="highlight">Farm</span> to Your Home</h1>
                <p>Support local farmers and enjoy fresh, organic produce today.</p>
                <div class="hero-buttons">
                    <a href="products.php" class="btn shop-btn"><i class="bi bi-cart4"></i> Shop Now</a>
                    <a href="about.php" class="btn info-btn"><i class="bi bi-info-circle"></i> Learn More</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="farm.jpg" alt="Farm Image">
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
