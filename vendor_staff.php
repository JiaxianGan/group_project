<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM staff WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $delete_id);
    $delete_stmt->execute();

    header("Location: vendor_subscriptions.php");
    exit();
}

// Fetch staff data for the logged-in vendor
$vendor_id = isset($_SESSION['vendor_id']) ? $_SESSION['vendor_id'] : 1;
$staffQuery = "
    SELECT * FROM staff 
    WHERE vendor_id = ?
";
$stmt = $conn->prepare($staffQuery);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$staffResult = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Staff Management</title>
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
        .btn-warning {
            color: white;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#">AgriMarket Vendor</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="vendors_dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendor_profile.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendor_orders.php">Orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendor_stock_alerts.php">Stock Alerts</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendor_subscriptions.php">Subscriptions</a></li>
                    <li class="nav-item"><a class="nav-link" href="vendor_staff.php">Staff Management</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="bg-white rounded-4 shadow p-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold"><i class="fas fa-users me-2"></i>Staff Management</h3>
            <a href="staff_add.php" class="btn btn-success"><i class="fas fa-plus me-1"></i>Add Staff</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th>Staff ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Full Name</th>
                        <th>Contact</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($staffResult && $staffResult->num_rows > 0) {
                        while ($row = $staffResult->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['staff_id']) . "</td>
                                    <td>" . htmlspecialchars($row['username']) . "</td>
                                    <td>" . htmlspecialchars($row['email']) . "</td>
                                    <td>" . htmlspecialchars($row['full_name']) . "</td>
                                    <td>" . htmlspecialchars($row['contact']) . "</td>
                                    <td>
                                        <a href='staff_edit.php?id=" . $row['staff_id'] . "' class='btn btn-warning btn-sm' title='Edit Staff'><i class='fas fa-edit'></i> Edit</a>
                                        <a href='staff_delete.php?id=" . $row['staff_id'] . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this staff member?');\" title='Delete Staff'><i class='fas fa-trash'></i> Delete</a>
                                    </td>
                                </tr>";
                        }
                        
                    } else {
                        echo "<tr><td colspan='6' class='text-danger text-center'>No staff members found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <a href="vendors_dashboard.php" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
