<?php
session_start();
include 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'add_user':
        $data = json_decode(file_get_contents('php://input'), true);
        
        $username = trim($data['username']);
        $email = trim($data['email']);
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $role = $data['role'];
        
        // Validate input
        if (empty($username) || empty($email) || empty($password) || empty($role)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required']);
            exit();
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Invalid email format']);
            exit();
        }
        
        // Check if username or email exists
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Username or email already exists']);
            $stmt->close();
            exit();
        }
        $stmt->close();
        
        // Insert user
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, status) VALUES (?, ?, ?, ?, 'Active')");
        $stmt->bind_param("ssss", $username, $email, $password, $role);
        
        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;
            echo json_encode(['success' => true, 'user_id' => $user_id]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create user']);
        }
        $stmt->close();
        break;

    case 'edit_user':
        $data = json_decode(file_get_contents('php://input'), true);
        
        $user_id = $data['user_id'];
        $email = trim($data['email']);
        $role = $data['role'];
        $status = $data['status'];
        
        // Validate input
        if (empty($email) || empty($role) || empty($status)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required']);
            exit();
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Invalid email format']);
            exit();
        }
        
        // Check if email is taken by another user
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
        $stmt->bind_param("si", $email, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Email already in use']);
            $stmt->close();
            exit();
        }
        $stmt->close();
        
        // Update user
        $stmt = $conn->prepare("UPDATE users SET email = ?, role = ?, status = ? WHERE user_id = ?");
        $stmt->bind_param("sssi", $email, $role, $status, $user_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update user']);
        }
        $stmt->close();
        break;

    case 'delete_user':
        $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
        
        if ($user_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
            exit();
        }
        
        // Prevent deleting the current admin
        if ($user_id == $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'Cannot delete your own account']);
            exit();
        }
        
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete user']);
        }
        $stmt->close();
        break;

    case 'reset_password':
        $data = json_decode(file_get_contents('php://input'), true);
        
        $user_id = $data['user_id'];
        $new_password = password_hash($data['new_password'], PASSWORD_DEFAULT);
        $force_change = $data['force_change'] ? 1 : 0;
        
        $stmt = $conn->prepare("UPDATE users SET password = ?, force_password_change = ? WHERE user_id = ?");
        $stmt->bind_param("sii", $new_password, $force_change, $user_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to reset password']);
        }
        $stmt->close();
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}

$conn->close();
?>