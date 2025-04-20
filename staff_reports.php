<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}
function getStoredReports($conn, $type) {
    $sql = "SELECT * FROM reports WHERE report_type = ? ORDER BY generated_at DESC LIMIT 5";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $type);
    $stmt->execute();
    return $stmt->get_result();
}
$pieData = [];
$pieQuery = "
    SELECT latest_status.status, COUNT(*) AS total_orders
    FROM (
        SELECT ot.order_id, ot.status
        FROM order_tracking ot
        INNER JOIN (
            SELECT order_id, MAX(updated_at) AS latest_update
            FROM order_tracking
            GROUP BY order_id
        ) latest
        ON ot.order_id = latest.order_id AND ot.updated_at = latest.latest_update
    ) AS latest_status
    GROUP BY latest_status.status
";
$pieResult = $conn->query($pieQuery);
if ($pieResult && $pieResult->num_rows > 0) {
    while ($row = $pieResult->fetch_assoc()) {
        $pieData[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AgriMarket - Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-image: url('reports_background.jpg');
            background-size: cover;
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
        .form-select {
            max-width: 200px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#"><i class="fas fa-tractor me-2"></i>AgriMarket</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="staff_dashboard.php"><i class="fas fa-home me-1"></i>Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="staff_delivery.php"><i class="fas fa-truck me-1"></i>Delivery</a></li>
                <li class="nav-item"><a class="nav-link" href="staff_products.php"><i class="fas fa-warehouse me-1"></i>Products</a></li>
                <li class="nav-item"><a class="nav-link active" href="staff_reports.php"><i class="fas fa-chart-line me-1"></i>Reports</a></li>
                <li class="nav-item"><a class="nav-link" href="staff_profile.php"><i class="fas fa-cog me-1"></i>Profile</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <div class="bg-white rounded-4 shadow p-5 mb-5">
        <h4 class="fw-bold mb-3"><i class="fas fa-chart-pie me-2"></i>Current Delivery Status</h4>
        <canvas id="deliveryStatusPie" class="mb-5" height="100"></canvas>
    </div>
</div>
<script>
    const pieCtx = document.getElementById('deliveryStatusPie').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: <?= json_encode(array_column($pieData, 'status')) ?>,
            datasets: [{
                label: 'Orders',
                data: <?= json_encode(array_column($pieData, 'total_orders')) ?>,
                backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#007bff'],
                borderWidth: 1
            }]
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>