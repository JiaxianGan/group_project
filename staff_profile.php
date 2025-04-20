<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}
$username_session = $_SESSION['username'];
$query = "SELECT user_id as staff_id, username, email FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $username_session);
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}
$result = $stmt->get_result();
$staff = $result->fetch_assoc();
if (!$staff) {
    die("No staff found with username: " . $username_session);
}
$staff_id = $staff['staff_id'];
$update_success = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        header("Location: auth.php");
        exit();
    }
    $new_username = $_POST['username'];
    $update_query = "UPDATE users SET username=? WHERE user_id=?";
    $update_stmt = $conn->prepare($update_query);
    if (!$update_stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $update_stmt->bind_param("si", $new_username, $staff_id);
    if ($update_stmt->execute()) {
        $_SESSION['username'] = $new_username;
        $update_success = true;
    }
    header("Location: staff_profile.php?update=success");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('reports_background.jpg');
            background-size: cover;
            background-attachment: fixed;
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
    </style>
</head>
<body>
<div class="container">    
    <?php if (isset($_GET['update']) && $_GET['update'] == 'success'): ?>
        <div class="alert alert-success text-center" role="alert">
            Your profile has been successfully updated!
        </div>
    <?php endif; ?>
    <div class="card p-5" style="background-color: white; max-width: 900px; margin: 0 auto; font-size: 1.2rem; box-shadow: 0 0 15px rgba(0,0,0,0.2);">
        <h2 class="text-center mb-4" style="font-size: 2rem;">Staff Profile</h2>
        <form method="POST" action="">
            <div class="mb-4">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control form-control-lg" id="username" name="username" value="<?php echo htmlspecialchars($staff['username']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control form-control-lg" id="email" value="<?php echo htmlspecialchars($staff['email']); ?>" readonly>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary btn-lg">Update Profile</button>
                <a href="staff_dashboard.php" class="btn btn-secondary btn-lg">Back</a>
            </div>
        </form>
        <form method="POST" action="" class="mt-4">
            <button type="submit" name="logout" class="btn btn-danger btn-lg btn-block">Logout</button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>