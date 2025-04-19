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
    <title>AgriMarket - Staff Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
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
            font-weight: 500;
        }

        .navbar .nav-link:hover {
            background-color: #1e7e34;
            border-radius: 5px;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
        }

        .card h5 {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="fas fa-tractor me-2"></i>AgriMarket</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="staff_dashboard.php"><i class="fas fa-home me-1"></i>Staff Panel</a></li>
                    <li class="nav-item"><a class="nav-link" href="staff_delivery.php"><i class="fas fa-truck me-1"></i>Delivery</a></li>
                    <li class="nav-item"><a class="nav-link" href="staff_vendors.php"><i class="fas fa-warehouse me-1"></i>Vendors</a></li>
                    <li class="nav-item"><a class="nav-link" href="staff_reports.php"><i class="fas fa-chart-line me-1"></i>Reports</a></li>
                    <li class="nav-item"><a class="nav-link" href="settings.php"><i class="fas fa-cog me-1"></i>Settings</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Panel -->
    <div class="container">
        <div class="p-5 bg-white rounded-4 shadow">
            <h2 class="mb-4">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

            <div class="row text-center mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-truck"></i> Deliveries</h5>
                            <p class="card-text fs-4">128</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-box-open"></i> Low Stock</h5>
                            <p class="card-text fs-4">6</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-users"></i> Vendors</h5>
                            <p class="card-text fs-4">12</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-chart-pie"></i> Reports</h5>
                            <p class="card-text fs-4">4</p>
                        </div>
                    </div>
                </div>
            </div>

            <p class="text-muted text-center">Use the navigation bar above to manage deliveries, inventory, vendors, and more.</p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>