<?php
session_start();
include 'db_connect.php';

// Fetch current user settings (assuming user_id is stored in session)
$user_id = $_SESSION['user_id'] ?? 1; // Change this based on your session setup
$query = "SELECT name, email FROM users WHERE id = $user_id";
$result = $conn->query($query);

if ($result && $row = $result->fetch_assoc()) {
    $name = $row['name'];
    $email = $row['email'];
} else {
    $name = "Default User";
    $email = "default@example.com";
}

// Update settings
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_name = $_POST['name'];
    $new_email = $_POST['email'];
    $new_password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
    
    $update_query = "UPDATE users SET name='$new_name', email='$new_email'";
    if ($new_password) {
        $update_query .= ", password='$new_password'";
    }
    $update_query .= " WHERE id=$user_id";
    
    if ($conn->query($update_query)) {
        $message = "Settings updated successfully!";
    } else {
        $message = "Error updating settings: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('settings_background.jpg');
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
        .settings-container {
            background: #28a745;
            color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }
        .settings-form {
            background: white;
            color: black;
            padding: 20px;
            border-radius: 10px;
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
                    <li class="nav-item"><a class="nav-link" href="reports.php">Reports</a></li>
                    <li class="nav-item"><a class="nav-link active" href="settings.php">Settings</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container settings-container">
        <h2 class="text-white">Settings</h2>
        <p>Update your profile and system preferences.</p>
        
        <?php if (isset($message)) { echo "<div class='alert alert-info'>$message</div>"; } ?>

        <div class="settings-form">
            <form method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($name); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">New Password (Leave blank if unchanged)</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <button type="submit" class="btn btn-success">Save Changes</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>