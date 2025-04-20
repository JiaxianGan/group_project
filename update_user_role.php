<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Authentication required']);
    exit();
}

// For debugging - log received data
error_log("Received update request: " . file_get_contents('php://input'));

// Get JSON data from request
$data = json_decode(file_get_contents('php://input'), true);

// Check if JSON was parsed correctly
if ($data === null) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid JSON data']);
    exit();
}

// Validate inputs
if (empty($data['userId']) || empty($data['newRole'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Missing required parameters']);
    exit();
}

$userId = intval($data['userId']);
$newRole = $conn->real_escape_string($data['newRole']);

// Validate role value
$allowedRoles = ['admin', 'vendor', 'staff', 'user'];
if (!in_array($newRole, $allowedRoles)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid role']);
    exit();
}

// Update user role in database
$sql = "UPDATE users SET role = '$newRole' WHERE user_id = $userId";

try {
    if ($conn->query($sql) === TRUE) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $conn->error]);
        error_log("MySQL Error: " . $conn->error);
    }
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Exception: ' . $e->getMessage()]);
    error_log("Exception: " . $e->getMessage());
}

$conn->close();
?>