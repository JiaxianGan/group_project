<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}

if (isset($_GET['id'])) {
    $staff_id = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $full_name = $_POST['full_name'];
        $contact = $_POST['contact'];

        $stmt = $conn->prepare("UPDATE staff SET username=?, email=?, full_name=?, contact=? WHERE staff_id=?");
        $stmt->bind_param("ssssi", $username, $email, $full_name, $contact, $staff_id);
        
        if ($stmt->execute()) {
            header("Location: vendor_staff.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt = $conn->prepare("SELECT * FROM staff WHERE staff_id=?");
    $stmt->bind_param("i", $staff_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $staff = $result->fetch_assoc();
} else {
    header("Location: vendor_staff.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('dashboard_background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .container {
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Edit Staff</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($staff['username']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($staff['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($staff['full_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="contact" class="form-label">Contact</label>
            <input type="text" class="form-control" id="contact" name="contact" value="<?php echo htmlspecialchars($staff['contact']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Staff</button>
        <a href="vendor_staff.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
