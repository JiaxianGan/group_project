<?php
session_start();
include 'db_connect.php'; // Use same DB file as your dashboard

// Redirect if not logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: auth.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$success_msg = $error_msg = "";

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $sql = "UPDATE customers SET name=?, email=?, address=?, phone=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $email, $address, $phone, $customer_id);

    if ($stmt->execute()) {
        $success_msg = "Profile updated successfully.";
    } else {
        $error_msg = "Error updating profile: " . $conn->error;
    }
}

// Fetch current customer data
$sql = "SELECT * FROM customers WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - AgriMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #f8f9fa;">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="bi bi-person-circle"></i> Edit Profile</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($success_msg): ?>
                            <div class="alert alert-success"><?php echo $success_msg; ?></div>
                        <?php elseif ($error_msg): ?>
                            <div class="alert alert-danger"><?php echo $error_msg; ?></div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($customer['name']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($customer['email']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($customer['address']); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($customer['phone']); ?>">
                            </div>

                            <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Update Profile</button>
                            <a href="customer_dashboard.php" class="btn btn-secondary ms-2"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons + JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
