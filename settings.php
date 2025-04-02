<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

$success_message = "";

if (isset($_POST['update_username'])) {
    $new_username = mysqli_real_escape_string($conn, $_POST['username']);
    mysqli_query($conn, "UPDATE users SET username='$new_username' WHERE user_id='$user_id'");
    $_SESSION['username'] = $new_username;
    $success_message = "Username updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function hideAlert() {
            let alertBox = document.getElementById("success-alert");
            if (alertBox) {
                setTimeout(() => {
                    alertBox.style.display = "none";
                }, 3000);
            }
        }
    </script>
    <style>
        body {
            background-image: url('settings_background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .settings-container {
            background: white;
            color: black;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            width: 90%;
            max-width: 1300px;
            margin-left: auto;
            margin-right: auto;
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
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        footer {
            text-align: center;
            padding: 10px;
            background-color: #155724;
            color: white;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body onload="hideAlert()">
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
        <h2 class="text-center">⚙️ Settings</h2>

        <?php if (!empty($success_message)) : ?>
            <div id="success-alert" class="alert alert-success text-center"><?= $success_message ?></div>
        <?php endif; ?>

        <div class="card p-4 mb-3">
            <h5><b>👤 Profile Information</b></h5>
            <form method="POST" class="d-flex align-items-center">
                <label class="me-2"><b>Username:</b></label>
                <input type="text" name="username" class="form-control me-2" value="<?= htmlspecialchars($_SESSION['username']) ?>" required>
                <button type="submit" name="update_username" class="btn btn-success">Update</button>
            </form>
            <p><b>📧 Email:</b> <?= htmlspecialchars($user['email']) ?></p>
            <p><b>🔖 Role:</b> <?= ucfirst($user['role']) ?></p>
            <p><b>📅 Date Joined:</b> <?= htmlspecialchars($user['created_at']) ?></p>
            <?php if ($user['role'] === 'vendor') : ?>
                <p><b>🌱 Products Uploaded:</b> <?= $products_uploaded ?></p>
            <?php endif; ?>
        </div>

        <div class="card p-4 mb-3">
            <h5><b>⚡ Account Management</b></h5>
            <a href="change_password.php" class="btn btn-info w-100 mb-2">🔑 Change Password</a>
            <a href="switch_account.php" class="btn btn-warning w-100 mb-2">🔄 Switch Account</a>
            <a href="logout.php" class="btn btn-dark w-100">🚪 Logout</a>
        </div>
    </div>
</body>
</html>