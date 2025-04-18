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
            background-image: url('dashboard_background.jpg');
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-search"></i> Search & Management
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="searchDropdown">
                            <li><a class="dropdown-item" href="#search-section"><i class="fas fa-search"></i> Product Search</a></li>
                            <li><a class="dropdown-item" href="#user-management-section"><i class="fas fa-users-cog"></i> User Management</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#role-section"><i class="fas fa-user-tag"></i> Role Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#system-section"><i class="fas fa-server"></i> System Overview</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="ordersDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-shopping-cart"></i> Orders & Products
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="ordersDropdown">
                            <li><a class="dropdown-item" href="#orders-section"><i class="fas fa-clipboard-list"></i> Recent Orders</a></li>
                            <li><a class="dropdown-item" href="#product-approval-section"><i class="fas fa-check-circle"></i> Product Approval</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#dynamic-data-section"><i class="fas fa-database"></i> Dynamic Data</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a class="nav-link" href="logout.php" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Dashboard Hero Section -->
    <div id="dashboard-hero" class="container dashboard-container section">
        <section class="hero">
            <div class="hero-text">
                <!-- Welcome Message -->
                <h2>Welcome, Admin <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
                <p>Here's a quick overview of your dashboard.</p>
                <h1>Admin Dashboard Overview</h1>
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Active Users
                        <span class="badge bg-primary rounded-pill">24</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Pending Orders
                        <span class="badge bg-warning rounded-pill">5</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        System Alerts
                        <span class="badge bg-danger rounded-pill">2</span>
                    </li>
                </ul>
                <div class="hero-buttons">
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

    <!-- Search and User Management Section -->
    <div id="search-section" class="container dashboard-container section">
        <h3 class="mb-4"><i class="fas fa-search"></i> Product Search</h3>
        <section class="search-user-management p-4 bg-white rounded shadow">
            <div class="search-container mb-3 w-100">
                <input type="text" placeholder="Search Products..." class="w-100">
                <button class="search-button"><i class="fas fa-search"></i></button>
            </div>
            <div id="search-results" class="mt-3">
                <!-- Search results will appear here -->
                <div class="alert alert-info">Enter a search term above to find products</div>
            </div>
        </section>
    </div>
    
    <div id="user-management-section" class="container dashboard-container section">
        <h3 class="mb-4"><i class="fas fa-users-cog"></i> User Management</h3>
        <section class="user-management p-4 bg-white rounded shadow">
            <!-- User Management Actions -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT user_id, username, role FROM users";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['user_id']}</td>
                                        <td>{$row['username']}</td>
                                        <td>{$row['role']}</td>
                                        <td>
                                            <button class='btn btn-sm btn-primary edit-user' data-user-id='{$row['user_id']}'><i class='fas fa-edit'></i> Edit</button>
                                            <button class='btn btn-sm btn-danger delete-user' data-user-id='{$row['user_id']}'><i class='fas fa-trash'></i> Delete</button>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No users found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <!-- Role Management and System Overview Section -->
    <div id="role-section" class="container dashboard-container section">
        <h3 class="mb-4"><i class="fas fa-user-tag"></i> Role Management</h3>
        <section class="role-system-overview p-4 bg-white rounded shadow">
            <!-- Role Management Form -->
            <form id="role-form" class="mb-4">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="user-select" class="col-form-label">Select User:</label>
                    </div>
                    <div class="col-auto">
                        <select id="user-select" class="form-select">
                            <option value="1">admin_user</option>
                            <option value="2">vendor_user</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <label for="role-select" class="col-form-label">Assign Role:</label>
                    </div>
                    <div class="col-auto">
                        <select id="role-select" name="role" class="form-select">
                            <option value="admin">Administrator</option>
                            <option value="vendor">Vendor</option>
                            <option value="user">Regular User</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-success">Change Role</button>
                    </div>
                </div>
            </form>
        </section>
    </div>
    
    <div id="system-section" class="container dashboard-container section">
        <h3 class="mb-4"><i class="fas fa-server"></i> System Overview</h3>
        <section class="system-overview p-4 bg-white rounded shadow">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-success text-white">
                            <i class="fas fa-server"></i> System Status
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Current Status</h5>
                            <p class="card-text">System Status: <span id="system-status" class="badge bg-info">Loading...</span></p>
                            <p class="card-text">Last Updated: <span id="last-updated">--</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <i class="fas fa-chart-bar"></i> System Metrics
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <?php
                                $sql = "SELECT active_users, server_load, db_status FROM system_metrics WHERE id = 1";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                                            Active Users
                                            <span class='badge bg-primary rounded-pill'>{$row['active_users']}</span>
                                          </li>
                                          <li class='list-group-item d-flex justify-content-between align-items-center'>
                                            Server Load
                                            <span class='badge bg-success rounded-pill'>{$row['server_load']}</span>
                                          </li>
                                          <li class='list-group-item d-flex justify-content-between align-items-center'>
                                            Database Connection
                                            <span class='badge bg-success rounded-pill'>{$row['db_status']}</span>
                                          </li>";
                                } else {
                                    echo "<li class='list-group-item'>No system metrics available</li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Recent Orders and Product Approval Section -->
    <div id="orders-section" class="container dashboard-container section">
        <h3 class="mb-4"><i class="fas fa-clipboard-list"></i> Recent Orders</h3>
        <section class="orders p-4 bg-white rounded shadow">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT order_id, customer_id, created_at, total_price, order_status FROM orders ORDER BY created_at DESC LIMIT 5";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['order_id']}</td>
                                        <td>{$row['customer_id']}</td>
                                        <td>{$row['created_at']}</td>
                                        <td>\${$row['total_price']}</td>
                                        <td><span class='badge bg-success'>{$row['order_status']}</span></td>
                                        <td>
                                            <button class='btn btn-sm btn-info view-order' data-order-id='{$row['order_id']}'><i class='fas fa-eye'></i> View</button>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No recent orders</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
    
    <div id="product-approval-section" class="container dashboard-container section">
        <h3 class="mb-4"><i class="fas fa-check-circle"></i> Product Approval</h3>
        <section class="product-approval p-4 bg-white rounded shadow">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Vendor</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Organic Apples</td>
                            <td>Fruits</td>
                            <td>Green Farm</td>
                            <td>$3.99/lb</td>
                            <td>
                                <button class="btn btn-sm btn-success approve-product" data-product-id="1"><i class="fas fa-check"></i> Approve</button>
                                <button class="btn btn-sm btn-danger reject-product" data-product-id="1"><i class="fas fa-times"></i> Reject</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Fresh Carrots</td>
                            <td>Vegetables</td>
                            <td>Harvest Valley</td>
                            <td>$2.49/lb</td>
                            <td>
                                <button class="btn btn-sm btn-success approve-product" data-product-id="2"><i class="fas fa-check"></i> Approve</button>
                                <button class="btn btn-sm btn-danger reject-product" data-product-id="2"><i class="fas fa-times"></i> Reject</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
                            <ul class="list-group dynamic-activities-list">
                                <li class="list-group-item">Loading activities...</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
    <script>
    // Search Functionality
    function handleSearch() {
        const searchInputs = document.querySelectorAll('.search-container input');
        const searchButtons = document.querySelectorAll('.search-button');
        
        searchButtons.forEach((button, index) => {
            button.addEventListener('click', () => {
                const query = searchInputs[index].value;
                // Perform AJAX request to search endpoint
                fetch(`admin_product_search.php?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Search results:', data);
                        // Update search results display
                        const searchResults = document.getElementById('search-results');
                        if (searchResults) {
                            if (data && data.length > 0) {
                                let resultsHtml = '<div class="list-group">';
                                data.forEach(item => {
                                    resultsHtml += `
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1">${item.name}</h5>
                                                <small>$${item.price}</small>
                                            </div>
                                            <p class="mb-1">${item.description}</p>
                                            <small>Category: ${item.category}</small>
                                        </a>
                                    `;
                                });
                                resultsHtml += '</div>';
                                searchResults.innerHTML = resultsHtml;
                            } else {
                                searchResults.innerHTML = '<div class="alert alert-warning">No results found</div>';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        const searchResults = document.getElementById('search-results');
                        if (searchResults) {
                            searchResults.innerHTML = '<div class="alert alert-danger">Error performing search</div>';
                        }
                    });
            });
        });

        // Also handle pressing Enter key in search inputs
        searchInputs.forEach((input, index) => {
            input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    searchButtons[index].click();
                }
            });
        });
    }

    // User Management
    function handleUserActions() {
        document.querySelectorAll('.edit-user').forEach(button => {
            button.addEventListener('click', () => {
                const userId = button.dataset.userId;
                console.log('Edit user:', userId);
                // Open edit modal or send AJAX request
                alert(`Opening edit form for user ID: ${userId}`);
            });
        });
        document.querySelectorAll('.delete-user').forEach(button => {
            button.addEventListener('click', () => {
                const userId = button.dataset.userId;
                console.log('Delete user:', userId);
                // Confirm and send AJAX request to delete user
                if (confirm(`Are you sure you want to delete user ID: ${userId}?`)) {
                    // Send delete request
                    alert(`User ID: ${userId} would be deleted (simulated)`);
                }
            });
        });
    }

    // Role Management
    function handleRoleChange() {
        const roleForm = document.querySelector('#role-form');
        if (roleForm) {
            roleForm.addEventListener('submit', (event) => {
                event.preventDefault();
                const formData = new FormData(roleForm);
                const userId = document.getElementById('user-select').value;
                const role = document.getElementById('role-select').value;
                
                console.log(`Changing role for user ID: ${userId} to ${role}`);
                
                fetch('/change-role', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Role change response:', data);
                    alert(`Role updated successfully for user ID: ${userId} (simulated)`);
                })
                .catch(error => {
                    console.error('Error changing role:', error);
                    alert('There was an error updating the role');
                });
            });
        }
    }

    // System Overview
    function updateSystemOverview() {
        fetch('/system-status')
            .then(response => response.json())
            .then(data => {
                const statusElement = document.getElementById('system-status');
                if (statusElement) {
                    statusElement.textContent = data.status || 'Online';
                    // Add appropriate class based on status
                    statusElement.className = 'badge ' + (data.status === 'Online' ? 'bg-success' : 'bg-warning');
                }
                
                const lastUpdatedElement = document.getElementById('last-updated');
                if (lastUpdatedElement) {
                    lastUpdatedElement.textContent = new Date().toLocaleString();
                }
                
                console.log('System status:', data);
            })
            .catch(error => {
                console.error('Error fetching system status:', error);
                const statusElement = document.getElementById('system-status');
                if (statusElement) {
                    statusElement.textContent = 'Unknown';
                    statusElement.className = 'badge bg-danger';
                }
            });
    }

    // Recent Orders
    function handleOrderView() {
        document.querySelectorAll('.view-order').forEach(button => {
            button.addEventListener('click', () => {
                const orderId = button.dataset.orderId;
                console.log('View order:', orderId);
                // Open order details modal or fetch order details
                alert(`Viewing details for Order ID: ${orderId}`);
            });
        });
    }

    // Product Approval
    function handleProductApproval() {
        document.querySelectorAll('.approve-product').forEach(button => {
            button.addEventListener('click', () => {
                const productId = button.dataset.productId;
                console.log('Approve product:', productId);
                // Send AJAX request to approve product
                fetch(`/approve-product/${productId}`, {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Product approval response:', data);
                    alert(`Product ID: ${productId} approved successfully (simulated)`);
                    // Could update UI here to remove the product from the approval list
                })
                .catch(error => {
                    console.error('Error approving product:', error);
                    alert('There was an error approving the product');
                });
            });
        });
        
        document.querySelectorAll('.reject-product').forEach(button => {
            button.addEventListener('click', () => {
                const productId = button.dataset.productId;
                console.log('Reject product:', productId);
                // Send AJAX request to reject product
                if (confirm(`Are you sure you want to reject product ID: ${productId}?`)) {
                    fetch(`/reject-product/${productId}`, {
                        method: 'POST'
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Product rejection response:', data);
                        alert(`Product ID: ${productId} rejected (simulated)`);
                        // Could update UI here to remove the product from the approval list
                    })
                    .catch(error => {
                        console.error('Error rejecting product:', error);
                        alert('There was an error rejecting the product');
                    });
                }
            });
        });
    }

    // Dynamic Data Loading - now includes chart creation
    function loadDynamicData() {
        fetch('/dashboard-data')
            .then(response => response.json())
            .then(data => {
                console.log('Dynamic data:', data);
                
                // Update activities list
                const activitiesList = document.querySelector('.dynamic-activities-list');
                if (activitiesList) {
                    if (data && data.activities && data.activities.length > 0) {
                        let activitiesHtml = '';
                        data.activities.forEach(activity => {
                            activitiesHtml += `
                                <li class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6>${activity.description}</h6>
                                        <small>${activity.time}</small>
                                    </div>
                                    <small class="text-muted">By: ${activity.user}</small>
                                </li>
                            `;
                        });
                        activitiesList.innerHTML = activitiesHtml;
                    } else {
                        activitiesList.innerHTML = '<li class="list-group-item">No recent activities</li>';
                    }
                }
                
                // Create sales chart
                const ctx = document.getElementById('salesChart');
                if (ctx && data && data.salesData) {
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.salesData.labels || ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                            datasets: [{
                                label: 'Sales ($)',
                                data: data.salesData.values || [12000, 19000, 15000, 21000, 18000],
                                backgroundColor: [
                                    'rgba(21, 87, 36, 0.6)',
                                    'rgba(21, 87, 36, 0.6)',
                                    'rgba(21, 87, 36, 0.7)',
                                    'rgba(21, 87, 36, 0.8)',
                                    'rgba(21, 87, 36, 0.9)',
                                    'rgba(21, 87, 36, 1.0)'
                                ],
                                borderColor: [
                                    'rgba(21, 87, 36, 1)',
                                    'rgba(21, 87, 36, 1)',
                                    'rgba(21, 87, 36, 1)',
                                    'rgba(21, 87, 36, 1)',
                                    'rgba(21, 87, 36, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            },
                            responsive: true
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error loading dynamic data:', error);
                
                // Handle error states for charts and activity list
                const activitiesList = document.querySelector('.dynamic-activities-list');
                if (activitiesList) {
                    activitiesList.innerHTML = '<li class="list-group-item text-danger">Error loading activities</li>';
                }
                
                // Create placeholder chart with error message
                const ctx = document.getElementById('salesChart');
                if (ctx) {
                    const fallbackData = {
                        labels: ['No Data'],
                        datasets: [{
                            label: 'Error Loading Sales Data',
                            data: [0],
                            backgroundColor: ['rgba(220, 53, 69, 0.6)'],
                            borderColor: ['rgba(220, 53, 69, 1)'],
                            borderWidth: 1
                        }]
                    };
                    
                    new Chart(ctx, {
                        type: 'bar',
                        data: fallbackData,
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            },
                            responsive: true
                        }
                    });
                }
            });
    }

    // Simulated data for demo when real endpoints aren't available
    function simulateDynamicData() {
        // Mock fetch response for system status
        window.fetch = function(url) {
            console.log('Simulated fetch:', url);
            
            return new Promise((resolve) => {
                let responseData = {};
                
                if (url === '/system-status') {
                    responseData = {
                        status: 'Online',
                        lastUpdated: new Date().toISOString()
                    };
                } else if (url === '/dashboard-data') {
                    responseData = {
                        salesData: {
                            labels: ['January', 'February', 'March', 'April', 'May'],
                            values: [12500, 19200, 15700, 21300, 18900]
                        },
                        activities: [
                            {
                                description: 'New product added',
                                time: '10 minutes ago',
                                user: 'vendor_user'
                            },
                            {
                                description: 'Order #143 completed',
                                time: '25 minutes ago',
                                user: 'system'
                            },
                            {
                                description: 'User role updated',
                                time: '1 hour ago',
                                user: 'admin_user'
                            },
                            {
                                description: 'System maintenance completed',
                                time: '3 hours ago',
                                user: 'system'
                            }
                        ]
                    };
                } else if (url.includes('/search')) {
                    const query = url.split('=')[1];
                    if (query && query.length > 0) {
                        responseData = [
                            {
                                id: 1,
                                name: 'Organic Apples',
                                description: 'Fresh organic apples from local farms',
                                category: 'Fruits',
                                price: '3.99'
                            },
                            {
                                id: 2,
                                name: 'Fresh Carrots',
                                description: 'Locally grown carrots, perfect for salads and cooking',
                                category: 'Vegetables',
                                price: '2.49'
                            }
                        ];
                    } else {
                        responseData = [];
                    }
                }
                
                const response = {
                    json: () => Promise.resolve(responseData)
                };
                resolve(response);
            });
        };
    }

    // Smooth scrolling for navigation links
    function setupSmoothScrolling() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    // Initialize all functions when DOM is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Setup simulation for demo purposes
        simulateDynamicData();
        
        // Initialize all components
        handleSearch();
        handleUserActions();
        handleRoleChange();
        updateSystemOverview();
        handleOrderView();
        handleProductApproval();
        loadDynamicData();
        setupSmoothScrolling();
        
        // Set initial system status
        document.getElementById('system-status').textContent = 'Online';
        document.getElementById('system-status').className = 'badge bg-success';
        document.getElementById('last-updated').textContent = new Date().toLocaleString();
        
        console.log('Dashboard initialized successfully');
    });
    </script>
</body>
</html>