<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["register"])) {
    $username = isset($_POST["username"]) ? trim($_POST["username"]) : '';
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : '';
    $confirm_password = isset($_POST["confirm_password"]) ? trim($_POST["confirm_password"]) : '';

    // Validate inputs
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['message'] = "All fields are required.";
        $_SESSION['message_type'] = "error";
        header("Location: auth.php");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['message'] = "Passwords do not match.";
        $_SESSION['message_type'] = "error";
        header("Location: auth.php");
        exit();
    }

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['message'] = "Username or Email already exists.";
        $_SESSION['message_type'] = "error";
        $stmt->close();
        header("Location: auth.php");
        exit();
    }
    $stmt->close();

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $role = "customer"; // Default role for registered users

    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO users (username, password_hash, email, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $password_hash, $email, $role);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Registration successful! You can now log in.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Something went wrong. Please try again.";
        $_SESSION['message_type'] = "error";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to auth.php
    header("Location: auth.php");
    exit();
}
?>
