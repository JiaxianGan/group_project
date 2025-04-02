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

if (isset($_POST['switch_account'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if (!$user || !password_verify($password, $user['password_hash'])) {
        $errors[] = "❌ Invalid email or password.";
    } else {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        
        echo "<script>
                alert('✅ Account switched successfully!');
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
    <title>Switch Account</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #28a745;
            color: white;
        }
        .switch-account-container {
            background: white;
            color: black;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 600px;
            margin: 50px auto;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
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
        <h2 class="text-center">🔄 Switch Account</h2>

        <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error) echo "<p>$error</p>"; ?>
            </div>
        <?php endif; ?>

        <div class="switch-account-container">
            <form method="POST">
                <label><b>📧 Gmail:</b></label>
                <input type="email" name="email" class="form-control" required>

                <label class="mt-2"><b>🔑 Password:</b></label>
                <input type="password" name="password" class="form-control" required>

                <button type="submit" name="switch_account" class="btn btn-primary mt-3 w-100">✔ Switch Account</button>
            </form>
        </div>
    </div>
</body>
</html>