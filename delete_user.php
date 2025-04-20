<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // CHANGE: Changed from die() to JSON response with HTTP status
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CHANGE: Changed from $_POST to JSON input
    $data = json_decode(file_get_contents('php://input'), true);
    
    // NEW: Added validation for JSON data
    if (!$data) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
        exit();
    }

    // CHANGE: Replaced intval($_POST['user_id']) with JSON input
    $user_id = intval($data['user_id'] ?? 0);

    // NEW: Added validation for user_id
    if ($user_id <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
        exit();
    }

    // NEW: Prevent deleting the current admin
    if ($user_id == ($_SESSION['user_id'] ?? 0)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Cannot delete your own account']);
        exit();
    }

    // NEW: Delete vendor record if exists
    $vendor_stmt = $conn->prepare("DELETE FROM vendors WHERE user_id = ?");
    $vendor_stmt->bind_param("i", $user_id);
    $vendor_stmt->execute();
    $vendor_stmt->close();

    // CHANGE: Replaced query with prepared statement
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $stmt->close();
        echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
    } else {
        $stmt->close();
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error deleting user: ' . $conn->error]);
    }
} else {
    // NEW: Added handling for non-POST requests
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>