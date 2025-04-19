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
    <title>Delivery Management - AgriMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
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
            font-weight: 500;
        }

        .navbar .nav-link:hover {
            background-color: #1e7e34;
            border-radius: 5px;
        }

        .table thead {
            background-color: #155724;
            color: white;
        }

        .container {
            margin-top: 50px;
        }

        .status-badge {
            font-size: 0.9rem;
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

    <!-- Delivery Management Table -->
    <div class="container">
        <div class="bg-white p-5 rounded-4 shadow">
            <h3 class="mb-4"><i class="fas fa-truck me-2"></i>Delivery Management</h3>

            <!-- Delivery Table -->
            <table class="table table-hover table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Address</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Delivery Status</th>
                        <th>Update Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>