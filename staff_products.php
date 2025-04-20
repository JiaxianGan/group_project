<?php
session_start();
include 'db_connect.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}
$username = $_SESSION['username'];
$currentPage = 'staff_products.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AgriMarket - Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('vendors_background.jpg');
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
        .table th {
            background-color: #155724;
            color: white;
        }
        .btn-success {
            background-color: #155724;
            border: none;
        }
        .btn-success:hover {
            background-color: #1e7e34;
        }
        .btn-warning {
            color: white;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#"><i class="fas fa-tractor me-2"></i>AgriMarket</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="staff_dashboard.php"><i class="fas fa-home me-1"></i>Staff Panel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="staff_delivery.php"><i class="fas fa-truck me-1"></i>Delivery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="staff_products.php"><i class="fas fa-warehouse me-1"></i>Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="staff_reports.php"><i class="fas fa-chart-line me-1"></i>Reports</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="staff_profile.php"><i class="fas fa-cog me-1"></i>Profile</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <div class="bg-white rounded-4 shadow p-5">
        <h3 class="fw-bold mb-4"><i class="fas fa-boxes me-2"></i>Product Inventory</h3>
        <?php
        if (isset($_SESSION['message'])) {
            echo '<div class="alert alert-info">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']);
        }
        ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Vendor</th>
                        <th>Category</th>
                        <th>Price (RM)</th>
                        <th>Stock</th>
                        <th>Added On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $productsQuery = "
                        SELECT p.*, v.vendor_name 
                        FROM products p 
                        LEFT JOIN vendors v ON p.vendor_id = v.vendor_id 
                        ORDER BY p.created_at DESC
                    ";
                    $products = $conn->query($productsQuery);
                    if ($products && $products->num_rows > 0) {
                        while ($row = $products->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['name']) . "</td>
                                    <td>" . htmlspecialchars($row['vendor_name']) . "</td>
                                    <td>" . htmlspecialchars(ucfirst($row['category'])) . "</td>
                                    <td>" . number_format($row['price'], 2) . "</td>
                                    <td>" . (int)$row['stock_quantity'] . "</td>
                                    <td>" . htmlspecialchars($row['created_at']) . "</td>
                                    <td>
                                        <a href='staff_edit_products.php?id=" . $row['product_id'] . "&page=$currentPage' class='btn btn-warning btn-sm'><i class='fas fa-edit'></i></a>
                                        <a href='staff_delete_products.php?id=" . $row['product_id'] . "&page=$currentPage' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this product?');\"><i class='fas fa-trash'></i></a>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-danger text-center'>No products found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>