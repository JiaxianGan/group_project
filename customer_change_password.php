<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: auth.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = trim($_POST["current_password"]);
    $new_password = trim($_POST["new_password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $message = "All fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $message = "New passwords do not match.";
    } else {
        $stmt = $conn->prepare("SELECT password_hash FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($password_hash_db);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($current_password, $password_hash_db)) {
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

            $update_stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
            $update_stmt->bind_param("si", $new_password_hash, $user_id);

            if ($update_stmt->execute()) {
                $success = true;
                $message = "Password changed successfully. Redirecting to dashboard...";
                header("refresh:3;url=customer_dashboard.php");
            } else {
                $message = "Failed to update password.";
            }

            $update_stmt->close();
        } else {
            $message = "Current password is incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .container {
            max-width: 500px;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        input[type="password"], button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
        }
        .message {
            margin: 10px 0;
            font-weight: bold;
            color: <?= $success ? 'green' : 'red' ?>;
        }
        a.back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        a.back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Change Password</h2>

    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (!$success): ?>
        <form method="post" action="">
            <label>Current Password:</label>
            <input type="password" name="current_password" required>

            <label>New Password:</label>
            <input type="password" name="new_password" required>

            <label>Confirm New Password:</label>
            <input type="password" name="confirm_password" required>

            <button type="submit">Change Password</button>
        </form>
    <?php endif; ?>

    <a href="customer_dashboard.php" class="back-btn">← Back to Dashboard</a>
</div>
</body>
</html>
