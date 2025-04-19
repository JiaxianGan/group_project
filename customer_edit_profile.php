<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: auth.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch existing user data
$sql = "SELECT username, email, phone, address FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    echo "<div style='padding:20px;color:red;'>User not found.</div>";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    $update_sql = "UPDATE users SET username = ?, email = ?, phone = ?, address = ? WHERE user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssi", $username, $email, $phone, $address, $user_id);

    if ($update_stmt->execute()) {
        $_SESSION['success_message'] = "Profile updated successfully!";
        header("Location: customer_view_profile.php");
        exit();
    } else {
        $error_message = "Error updating profile: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - AgriMarket Solutions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body style="background-color: #f8f9fa;">
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4><i class="bi bi-pencil-square"></i> Edit Profile</h4>
        </div>
        <div class="card-body">
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label fw-bold"><i class="bi bi-person-fill"></i> Username:</label>
                    <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold"><i class="bi bi-envelope-fill"></i> Email:</label>
                    <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold"><i class="bi bi-phone-fill"></i> Phone:</label>
                    <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($user['phone']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold"><i class="bi bi-geo-alt-fill"></i> Address:</label>
                    <textarea class="form-control" name="address" rows="3" required><?= htmlspecialchars($user['address']); ?></textarea>
                </div>
                <div class="mb-3 text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
        <div class="card-footer text-end">
            <a href="customer_view_profile.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left-circle"></i> Back to Profile
            </a>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
