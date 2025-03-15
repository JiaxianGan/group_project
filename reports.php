<?php
session_start();
include 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('reports_background.jpg');
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
        .report-container {
            background: #28a745;
            color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }
        .table {
            background: white;
            border-radius: 10px;
        }
        .export-buttons {
            margin-bottom: 20px;
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
                    <li class="nav-item"><a class="nav-link active" href="reports.php">Reports</a></li>
                    <li class="nav-item"><a class="nav-link" href="settings.php">Settings</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container report-container">
        <h2 class="text-white">Business Reports</h2>
        <p>View key metrics and insights about your business performance.</p>

        <div class="export-buttons">
            <a href="export_csv.php" class="btn btn-light">Export CSV</a>
            <a href="export_pdf.php" class="btn btn-light">Export PDF</a>
        </div>

        <!-- Sales Report -->
        <h4 class="text-white">Sales Report</h4>
        <table class="table table-bordered">
            <thead class="table-success">
                <tr>
                    <th>Total Orders</th>
                    <th>Total Revenue (RM)</th>
                </tr>
            </thead>
        </table>

        <!-- Inventory Report -->
        <h4 class="text-white mt-4">Inventory Report</h4>
        <table class="table table-bordered">
            <thead class="table-warning">
                <tr>
                    <th>Product</th>
                    <th>Stock Available</th>
                    <th>Price (RM)</th>
                </tr>
            </thead>
        </table>

        <!-- Vendor Performance Report -->
        <h4 class="text-white mt-4">Vendor Performance Report</h4>
        <table class="table table-bordered">
            <thead class="table-primary">
                <tr>
                    <th>Vendor Name</th>
                    <th>Total Sales</th>
                    <th>Total Revenue (RM)</th>
                </tr>
            </thead>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>