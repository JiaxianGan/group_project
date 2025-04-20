<?php
session_start();
include 'db_connect.php';

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-image: url('admin_background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            padding-top: 56px; /* Account for fixed navbar */
        }
        .navbar {
            background-color: #1a3d2f !important; /* Darker green for a modern look */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .navbar .nav-link {
            color: #f8f9fa !important;
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            transition: background-color 0.3s ease, color 0.3s ease;
            white-space: nowrap; /* Prevent text from wrapping */
        }
        .navbar .nav-link:hover {
            background-color: #145a32;
            color: #ffffff;
            border-radius: 5px;
        }
        .navbar .nav-link i {
            margin-right: 8px;
            font-size: 1.1rem;
        }
        .navbar-nav .dropdown-menu {
            background-color: #1a3d2f;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .navbar-nav .dropdown-item {
            color: #f8f9fa;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .navbar-nav .dropdown-item:hover {
            background-color: #145a32;
            color: #ffffff;
        }
        .dropdown-submenu {
            position: relative;
        }
        .dropdown-submenu .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -1px;
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
        .section {
            scroll-margin-top: 70px; /* For smooth scrolling with fixed navbar */
        }
        /* New styles for the dashboard */
        .card {
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .status-indicator {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        .status-good {
            background-color: #28a745;
        }
        .status-warning {
            background-color: #ffc107;
        }
        .status-danger {
            background-color: #dc3545;
        }
        .metric-card {
            padding: 15px;
            border-radius: 8px;
            color: white;
            margin-bottom: 15px;
        }
        .bg-gradient-success {
            background: linear-gradient(45deg, #28a745, #20c997);
        }
        .bg-gradient-info {
            background: linear-gradient(45deg, #17a2b8, #0dcaf0);
        }
        .bg-gradient-warning {
            background: linear-gradient(45deg, #ffc107, #fd7e14);
        }
        .activity-item {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .activity-item:last-child {
            border-bottom: none;
        }
        .activity-time {
            font-size: 0.8rem;
            color: #6c757d;
        }
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }
        .progress-thin {
            height: 6px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#dashboard-hero"><i class="fas fa-home"></i> AgriMarket</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#user-management-section"><i class="fas fa-users-cog"></i> User Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#system-section"><i class="fas fa-server"></i> System Overview</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#dynamic-data-section"><i class="fas fa-database"></i> Dynamic Data</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <div class="dropdown me-3">
                        <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            <span class="badge bg-danger rounded-pill" id="notification-badge">3</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" id="notification-menu">
                            <li><a class="dropdown-item" href="#">Server load high (85%)</a></li>
                            <li><a class="dropdown-item" href="#">New user registration</a></li>
                            <li><a class="dropdown-item" href="#">Database backup completed</a></li>
                        </ul>
                    </div>
                    <a class="nav-link" href="logout.php" title="Logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Toast Notifications Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Dashboard Hero Section -->
    <div id="dashboard-hero" class="container dashboard-container section">
        <section class="hero">
            <div class="hero-text">
                <!-- Welcome Message -->
                <h2>Welcome, Admin <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
                <p>Here's a quick overview of your dashboard.</p>
                <h1>Admin Dashboard Overview</h1>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="metric-card bg-gradient-success">
                            <h5><i class="fas fa-users"></i> Active Users</h5>
                            <h2 id="active-users-count">24</h2>
                            <div class="progress progress-thin mt-2">
                                <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="metric-card bg-gradient-info">
                            <h5><i class="fas fa-shopping-cart"></i> Pending Orders</h5>
                            <h2 id="pending-orders-count">5</h2>
                            <div class="progress progress-thin mt-2">
                                <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="metric-card bg-gradient-warning">
                            <h5><i class="fas fa-exclamation-triangle"></i> System Alerts</h5>
                            <h2 id="system-alerts-count">2</h2>
                            <div class="progress progress-thin mt-2">
                                <div class="progress-bar" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hero-buttons mt-4">
                    <a href="#dynamic-data-section" class="btn btn-outline-success">View Dynamic Data</a>
                    <a href="#user-management-section" class="btn btn-outline-primary">Manage Users</a>
                    <a href="#system-section" class="btn btn-outline-danger">System Overview</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="admin_dashboard.jpg" alt="Admin Dashboard Image">
            </div>
        </section>
    </div>

    <!-- User Management Section -->
    <div id="user-management-section" class="container dashboard-container section">
        <h3 class="mb-4"><i class="fas fa-users-cog"></i> User Management</h3>
        <section class="user-management p-4 bg-white rounded shadow">
            <!-- User Management Controls -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <input type="text" id="userSearchInput" class="form-control" placeholder="Search users...">
                </div>
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-success" id="addUserBtn">
                        <i class="fas fa-user-plus"></i> Add New User
                    </button>
                </div>
            </div>
            
            <!-- User List -->
            <div class="table-responsive">
                <table class="table table-striped" id="usersTable">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Actions</th>
                            <th>Last Login</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT user_id, username, role FROM users ORDER BY user_id DESC LIMIT 10";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                // Random status and last login for demonstration
                                $statuses = ["Active", "Inactive"];
                                $status = $statuses[array_rand($statuses)];
                                $statusClass = $status == "Active" ? "text-success" : "text-secondary";
                                $lastLogin = date("Y-m-d H:i:s", strtotime("-" . rand(1, 30) . " hours"));
                                
                                echo "<tr>
                                        <td>{$row['user_id']}</td>
                                        <td>{$row['username']}</td>
                                        <td>{$row['role']}</td>
                                        <td><span class='$statusClass'><i class='fas fa-circle fa-sm me-1'></i>$status</span></td>
                                        <td>$lastLogin</td>
                                        <td>
                                            <button class='btn btn-sm btn-primary edit-user' data-user-id='{$row['user_id']}' data-username='{$row['username']}' data-role='{$row['role']}'><i class='fas fa-edit'></i></button>
                                            <button class='btn btn-sm btn-danger delete-user' data-user-id='{$row['user_id']}'><i class='fas fa-trash'></i></button>
                                            <button class='btn btn-sm btn-info reset-password' data-user-id='{$row['user_id']}'><i class='fas fa-key'></i></button>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No users found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <span>Showing <strong>1</strong> to <strong>10</strong> of <strong id="total-users">25</strong> users</span>
                </div>
                <ul class="pagination">
                    <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </div>
        </section>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-user-form" action="add_user.php" method="POST">
                        <div class="mb-3">
                            <label for="add-username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="add-username" required>
                        </div>
                        <div class="mb-3">
                            <label for="add-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="add-email" required>
                        </div>
                        <div class="mb-3">
                            <label for="add-password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="add-password" required>
                        </div>
                        <div class="mb-3">
                            <label for="add-role" class="form-label">Role</label>
                            <select id="add-role" class="form-select" required>
                                <option value="">Select Role</option>
                                <option value="admin">Administrator</option>
                                <option value="vendor">Vendor</option>
                                <option value="staff">Staff</option>
                                <option value="user">Regular User</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Add User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-user-form" action="edit_user.php" method="POST">
                        <input type="hidden" id="edit-user-id">
                        <div class="mb-3">
                            <label for="edit-username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="edit-username" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="edit-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit-email">
                        </div>
                        <div class="mb-3">
                            <label for="edit-role" class="form-label">Role</label>
                            <select id="edit-role" class="form-select">
                                <option value="admin">Administrator</option>
                                <option value="vendor">Vendor</option>
                                <option value="staff">Staff</option>
                                <option value="user">Regular User</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="reset-password-form" action="reset_password.php" method="POST">
                        <input type="hidden" id="reset-user-id">
                        <div class="mb-3">
                            <label for="reset-username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="reset-username" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="new-password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new-password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm-password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm-password" required>
                        </div>
                        <button type="submit" class="btn btn-warning">Reset Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the user <strong id="delete-username"></strong>?</p>
                    <p class="text-danger">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <!-- CHANGE: Added form for delete submission -->
                    <form id="delete-user-form" action="delete_user.php" method="POST">
                        <input type="hidden" id="delete-user-id" name="user_id">
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- System Overview Section -->
    <div id="system-section" class="container dashboard-container section">
        <h3 class="mb-4"><i class="fas fa-server"></i> System Overview</h3>
        <section class="system-overview p-4 bg-white rounded shadow">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-success text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-server"></i> System Status</span>
                                <button class="btn btn-sm btn-light" id="refreshSystemStatus"><i class="fas fa-sync-alt"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <h5>Server Status</h5>
                                <span id="system-status" class="badge bg-success">Online</span>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>CPU Usage</span>
                                    <span id="cpu-usage">45%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 45%" id="cpu-progress"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Memory Usage</span>
                                    <span id="memory-usage">65%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 65%" id="memory-progress"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Disk Usage</span>
                                    <span id="disk-usage">32%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 32%" id="disk-progress"></div>
                                </div>
                            </div>
                            <p class="card-text mt-3">Last Updated: <span id="last-updated"><?php echo date('Y-m-d H:i:s'); ?></span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <i class="fas fa-chart-bar"></i> System Metrics
                        </div>
                        <div class="card-body">
                            <canvas id="systemMetricsChart" width="400" height="250"></canvas>
                            <div class="list-group mt-3">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    Active Users
                                    <span class="badge bg-primary rounded-pill" id="active-users">24</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    Server Load
                                    <span class="badge bg-success rounded-pill" id="server-load">Normal</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    Database Connection
                                    <span class="badge bg-success rounded-pill" id="db-status">Connected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- System Logs -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-list-alt"></i> System Logs</span>
                                <div>
                                    <select class="form-select form-select-sm" id="log-filter">
                                        <option value="all">All Logs</option>
                                        <option value="error">Errors</option>
                                        <option value="warning">Warnings</option>
                                        <option value="info">Information</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm" id="systemLogsTable">
                                    <thead>
                                        <tr>
                                            <th>Time</th>
                                            <th>Type</th>
                                            <th>Message</th>
                                            <th>Source</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Log entries will be populated by JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Dynamic Data Loading Section -->
    <div id="dynamic-data-section" class="container dashboard-container section">
        <h3 class="mb-4"><i class="fas fa-database"></i> Dynamic Data</h3>
        <section class="dynamic-data p-4 bg-white rounded shadow">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-success text-white">
                            <i class="fas fa-chart-pie"></i> Sales Overview
                        </div>
                        <div class="card-body">
                            <canvas id="salesChart" width="400" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                <div class="card">
                        <div class="card-header bg-success text-white">
                            <i class="fas fa-list"></i> Latest Activities
                        </div>
                        <div class="card-body">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-filter"></i></span>
                                <select class="form-select" id="activity-filter">
                                    <option value="all">All Activities</option>
                                    <option value="login">Login</option>
                                    <option value="data">Data Changes</option>
                                    <option value="system">System Events</option>
                                </select>
                            </div>
                            <ul class="list-group" id="activity-list">
                                <!-- Activities will be loaded by JavaScript -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- User Activity Analysis -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <i class="fas fa-users-cog"></i> User Activity Analytics
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Login Traffic</h5>
                                            <canvas id="loginTrafficChart" width="400" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">User Roles Distribution</h5>
                                            <canvas id="userRolesChart" width="400" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped" id="userActivityTable">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Last Login</th>
                                            <th>Actions Performed</th>
                                            <th>Login Location</th>
                                            <th>Device</th>
                                            <th>IP Address</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- User activity data will be loaded by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
    <script src="admin_dashboard.js"></script>
</body>
</html>