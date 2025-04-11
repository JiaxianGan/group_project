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
    <title>AgriMarket Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .navbar .nav-link {
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
            text-align: left;
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
        .hero-text p {
            font-size: 1.2rem;
            color: #333;
        }
        .hero-buttons {
            margin-top: 20px;
        }
        .hero-buttons button {
            padding: 12px 20px;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-right: 10px;
            transition: all 0.3s ease;
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
            .hero-text {
                max-width: 100%;
            }
            .hero-image {
                max-width: 100%;
                margin-top: 20px;
            }
            .hero-text h1 {
                font-size: 2rem;
            }
            .hero-buttons button {
                padding: 10px 15px;
                font-size: 0.9rem;
            }
        }
        .search-container {
            display: flex;
            align-items: center;
            background: white;
            padding: 5px;
            border-radius: 5px;
            margin-right: 20px;
        }
        .search-container input {
            border: none;
            padding: 5px;
            outline: none;
            width: 200px;
        }
        .search-container button {
            background: none;
            border: none;
            cursor: pointer;
        }
        .icons span {
            margin-left: 10px;
            cursor: pointer;
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
                    <div class="search-container">
                        <input type="text" placeholder="Search Products...">
                        <button>🔍</button>
                        <div class="icons">
                            <span>👤</span>
                            <span>🛒</span>
                        </div>
                    </div>
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

    <!-- Dashboard Hero Section -->
    <div class="container dashboard-container">
        <section class="hero">
            <div class="hero-text">
                <!-- Welcome Message -->
                <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
                <p>Enjoy managing your agricultural market.</p>
                <a href="about.php" class="about-btn btn btn-outline-success">ABOUT OUR FARM</a>
                <h1>The Place Where <br> <span class="highlight">Natural Flavour</span> is Born</h1>
                <p>We bring you fresh, natural flavors straight from the farm. Experience the best quality produce with us.</p>
                <div class="hero-buttons">
                    <a href="products.php" class="shop-btn btn">Shop Online</a>
                    <a href="vendors.php" class="info-btn btn">Know More</a>
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