<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["login"])) {
        $username = isset($_POST["username"]) ? trim($_POST["username"]) : '';
        $password = isset($_POST["password"]) ? trim($_POST["password"]) : '';

        // Check if fields are empty
        if (empty($username) || empty($password)) {
            $_SESSION['error'] = "All fields are required.";
            header("Location: auth.php");
            exit();
        }

        // Debugging: Check database connection
        if (!$conn) {
            die("Database connection failed: " . mysqli_connect_error());
        }

        // Check if the user exists
        $stmt = $conn->prepare("SELECT user_id, username, password_hash, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        // Debugging: Print number of matched rows
        if ($stmt->num_rows === 0) {
            $_SESSION['error'] = "User not found.";
            header("Location: auth.php");
            exit();
        }

        $stmt->bind_result($user_id, $db_username, $db_password_hash, $role);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $db_password_hash)) {
            $_SESSION["user_id"] = $user_id;
            $_SESSION["username"] = $db_username;
            $_SESSION["role"] = $role;

            // Redirect to dashboard.php
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Incorrect password.";
            header("Location: auth.php");
            exit();
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Invalid request.";
        header("Location: auth.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Access denied.";
    header("Location: auth.php");
    exit();
}

$conn->close();
?>
