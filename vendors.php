<?php
session_start();
include 'db_connect.php';

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch vendors from the database
$query = "SELECT * FROM vendors";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendors</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('vendors_background.jpg'); /* Replace with your image path */
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
        .vendor-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }
        .search-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .search-container input {
            padding: 8px;
            width: 250px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">AgriMarket</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse w-100" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="orders.php">Orders</a></li>
                    <li class="nav-item"><a class="nav-link active" href="vendors.php">Vendors</a></li>
                    <li class="nav-item"><a class="nav-link" href="reports.php">Reports</a></li>
                    <li class="nav-item"><a class="nav-link" href="settings.php">Settings</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container vendor-container">
        <h2 class="text-success">Vendor Management</h2>
        <p>Manage your vendors and suppliers efficiently.</p>

        <!-- Search Bar -->
        <div class="search-container">
            <input type="text" id="vendorSearch" placeholder="Search vendor..." onkeyup="filterVendors()">
            <a href="add_vendor.php" class="btn btn-success">Add New Vendor</a>
        </div>

        <!-- Vendor Table -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-success">
                    <tr>
                        <th>ID</th>
                        <th>Vendor Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="vendorTable">
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['vendor_id']; ?></td>
                            <td><?php echo $row['vendor_name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['contact']; ?></td>
                            <td>
                                <a href="edit_vendor.php?id=<?php echo $row['vendor_id']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete_vendor.php?id=<?php echo $row['vendor_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this vendor?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

    <!-- Search Filter Script -->
    <script>
        function filterVendors() {
            let input = document.getElementById("vendorSearch").value.toLowerCase();
            let rows = document.querySelectorAll("#vendorTable tr");

            rows.forEach(row => {
                let name = row.cells[1].textContent.toLowerCase();
                row.style.display = name.includes(input) ? "" : "none";
            });
        }
    </script>

</body>
</html>