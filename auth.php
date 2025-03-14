<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {  // LOGIN PROCESS
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] == 'admin') {
                header("Location: admin_dashboard.php");
            } elseif ($user['role'] == 'vendor') {
                header("Location: vendor_dashboard.php");
            } elseif ($user['role'] == 'staff') {
                header("Location: staff_dashboard.php");
            } else {
                header("Location: customer_dashboard.php");
            }
            exit();
        } else {
            echo "<script>alert('Invalid email or password.');</script>";
        }
    }

    if (isset($_POST['register'])) {  // REGISTRATION PROCESS
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = 'customer'; // Only customers can register

        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Email already exists.');</script>";
        } else {
            $query = "INSERT INTO users (username, password_hash, email, role) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssss", $username, $hashed_password, $email, $role);
            if ($stmt->execute()) {
                echo "<script>alert('Registration successful! You can now log in.'); window.location='auth.php';</script>";
            } else {
                echo "<script>alert('Error in registration. Try again.');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="auth-page">

    <div class="container">
        <div class="box">
            <!-- Login Section (Left) -->
            <div class="left">
                <h2>Login</h2>
                <form action="login_process.php" method="post">
                    <input type="text" name="username" placeholder="Enter Username" required>
                    <input type="password" name="password" placeholder="Enter Password" required>
                    <button type="submit" name="login">Login</button>
                </form>
                <p>Don't have an account? <a href="#" id="show-register">Sign Up</a></p>
            </div>

            <!-- Register Section (Right) -->
            <div class="right">
                <h2>Sign Up</h2>
                <form action="register_process.php" method="POST">
                    <input type="text" name="username" placeholder="Enter Username">
                    <input type="email" name="email" placeholder="Enter Email">
                    <input type="password" name="password" placeholder="Enter Password">
                    <input type="password" name="confirm_password" placeholder="Confirm Password">
                    <button type="submit" name="register">Register</button>
                </form>
                <p>Already have an account? <a href="#" id="show-login">Log In</a></p>
            </div>
        </div>
    </div>

    <script>
        // JavaScript for switching between login and register
        document.getElementById("show-register").addEventListener("click", function() {
            document.querySelector(".left").style.display = "none";
            document.querySelector(".right").style.display = "flex";
        });

        document.getElementById("show-login").addEventListener("click", function() {
            document.querySelector(".right").style.display = "none";
            document.querySelector(".left").style.display = "flex";
        });
    </script>

    <?php
    session_start();
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $message_type = $_SESSION['message_type'];

        echo "<script>
            alert('$message');
        </script>";

        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
    ?>

</body>
</html>


