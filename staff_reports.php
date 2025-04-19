<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}
$filter = $_GET['filter'] ?? 'daily';
$type = $_GET['type'] ?? 'sales';
switch ($filter) {
    case 'weekly':
        $group_by = "YEARWEEK(o.order_date)";
        $label = "Week";
        break;
    case 'monthly':
        $group_by = "DATE_FORMAT(o.order_date, '%Y-%m')";
        $label = "Month";
        break;
    default:
        $group_by = "DATE(o.order_date)";
        $label = "Date";
        break;
}
function getStoredReports($conn, $type) {
    $sql = "SELECT * FROM reports WHERE report_type = ? ORDER BY generated_at DESC LIMIT 5";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $type);
    $stmt->execute();
    return $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AgriMarket - Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
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
        .text-small {
            font-size: 0.85rem;
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold"><i class="fas fa-chart-line me-2"></i>Sales Report (<?= ucfirst($filter) ?>)</h3>
            <form method="get" class="d-flex gap-2">
                <input type="hidden" name="type" value="sales">
                <select name="filter" class="form-select" onchange="this.form.submit()">
                    <option value="daily" <?= $filter === 'daily' ? 'selected' : '' ?>>Daily</option>
                    <option value="weekly" <?= $filter === 'weekly' ? 'selected' : '' ?>>Weekly</option>
                    <option value="monthly" <?= $filter === 'monthly' ? 'selected' : '' ?>>Monthly</option>
                </select>
            </form>
        </div>
        <div class="table-responsive mb-5">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th><?= $label ?></th>
                        <th>Total Orders</th>
                        <th>Total Revenue (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $reportQuery = "
                        SELECT $group_by AS grouped_date, COUNT(*) AS order_count, SUM(total_amount) AS revenue 
                        FROM orders o 
                        GROUP BY grouped_date 
                        ORDER BY grouped_date DESC
                    ";
                    $reportResult = $conn->query($reportQuery);
                    if ($reportResult && $reportResult->num_rows > 0) {
                        while ($row = $reportResult->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['grouped_date']) . "</td>
                                    <td>" . (int)$row['order_count'] . "</td>
                                    <td>" . number_format($row['revenue'], 2) . "</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' class='text-danger text-center'>No report data found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <h4 class="fw-bold mb-3"><i class="fas fa-database me-2"></i>Stored Reports</h4>
        <form method="get" class="mb-3">
            <input type="hidden" name="filter" value="<?= $filter ?>">
            <select name="type" class="form-select w-auto d-inline-block" onchange="this.form.submit()">
                <option value="sales" <?= $type === 'sales' ? 'selected' : '' ?>>Sales</option>
                <option value="most_searched" <?= $type === 'most_searched' ? 'selected' : '' ?>>Most Searched</option>
                <option value="most_visited" <?= $type === 'most_visited' ? 'selected' : '' ?>>Most Visited</option>
                <option value="popular_orders" <?= $type === 'popular_orders' ? 'selected' : '' ?>>Popular Orders</option>
            </select>
        </form>
        <?php
        $storedReports = getStoredReports($conn, $type);
        if ($storedReports && $storedReports->num_rows > 0) {
            while ($report = $storedReports->fetch_assoc()) {
                echo "<div class='card mb-3'>
                        <div class='card-header bg-success text-white'>
                            " . ucfirst(str_replace('_', ' ', $report['report_type'])) . " | Generated At: " . $report['generated_at'] . "
                        </div>
                        <div class='card-body'>
                            <pre class='mb-0'>" . htmlspecialchars($report['data']) . "</pre>
                        </div>
                    </div>";
            }
        } else {
            echo "<div class='alert alert-info'>No stored reports available for this type.</div>";
        }
        ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>