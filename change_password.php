<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$errors = [];
$success = "";

if (isset($_POST['change_password'])) {
    $old_password = mysqli_real_escape_string($conn, $_POST['old_password']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    $query = "SELECT password_hash FROM users WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if (!$user || !password_verify($old_password, $user['password_hash'])) {
        $errors[] = "❌ Old password is incorrect.";
    } elseif ($old_password === $new_password) {
        $errors[] = "❌ New password cannot be the same as the old password.";
    } elseif ($new_password !== $confirm_password) {
        $errors[] = "❌ New passwords do not match.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE users SET password_hash='$hashed_password' WHERE user_id='$user_id'");
        
        echo "<script>
                alert('✅ Password updated successfully!');
                window.location.href = 'settings.php';
              </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #28a745;
            color: white;
        }
        .change-password-container {
            background: white;
            color: black;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 600px;
            margin: 50px auto;
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
<body>
    <div class="container mt-4">
        <div class="text-end">
            <a href="settings.php" class="btn btn-dark btn-sm">⬅ Back</a>
        </div>
        <h2 class="text-center">🔑 Change Password</h2>

        <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error) echo "<p>$error</p>"; ?>
            </div>
        <?php endif; ?>

        <div class="change-password-container">
            <form method="POST">
                <label><b>Old Password:</b></label>
                <input type="password" name="old_password" class="form-control" required>

                <label class="mt-2"><b>New Password:</b></label>
                <input type="password" name="new_password" class="form-control" required>

                <label class="mt-2"><b>Confirm New Password:</b></label>
                <input type="password" name="confirm_password" class="form-control" required>

                <button type="submit" name="change_password" class="btn btn-success mt-3 w-100">✔ Update Password</button>
            </form>
        </div>
    </div>
</body>
</html>