<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}

// Handle editing a subscription
if (isset($_GET['id'])) {
    $edit_id = $_GET['id'];
    $edit_query = "SELECT plan_name, price, duration FROM subscriptions WHERE id = ?";
    $edit_stmt = $conn->prepare($edit_query);
    $edit_stmt->bind_param("i", $edit_id);
    $edit_stmt->execute();
    $edit_result = $edit_stmt->get_result();
    $edit_subscription = $edit_result->fetch_assoc();
}

// Update subscription
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_subscription'])) {
    $update_id = $_POST['update_id'];
    $plan_name = $_POST['plan_name'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];

    $update_query = "UPDATE subscriptions SET plan_name=?, price=?, duration=? WHERE id=?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sdii", $plan_name, $price, $duration, $update_id);
    $update_stmt->execute();

    header("Location: vendor_subscriptions.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subscription Plan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('dashboard_background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .container {
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Subscription Plan</h1>
        <form method="POST" action="edit_subscription.php?id=<?php echo $edit_id; ?>">
            <input type="hidden" name="update_id" value="<?php echo $edit_id; ?>">
            <div class="mb-3">
                <label for="plan_name" class="form-label">Plan Name</label>
                <select class="form-select" id="plan_name" name="plan_name" required>
                    <option value="" disabled>Select a plan</option>
                    <option value="Basic" <?php echo $edit_subscription['plan_name'] == 'Basic' ? 'selected' : ''; ?>>Basic</option>
                    <option value="Premium" <?php echo $edit_subscription['plan_name'] == 'Premium' ? 'selected' : ''; ?>>Premium</option>
                    <option value="Enterprise" <?php echo $edit_subscription['plan_name'] == 'Enterprise' ? 'selected' : ''; ?>>Enterprise</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" required value="<?php echo htmlspecialchars($edit_subscription['price']); ?>">
            </div>
            <div class="mb-3">
                <label for="duration" class="form-label">Duration (Months)</label>
                <select class="form-select" id="duration" name="duration" required>
                    <option value="" disabled>Select duration</option>
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo $edit_subscription['duration'] == $i ? 'selected' : ''; ?>>
                            <?php echo $i; ?> Month<?php echo $i > 1 ? 's' : ''; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <button type="submit" name="update_subscription" class="btn btn-primary">Update Subscription</button>
        </form>
        <a href="vendor_subscriptions.php" class="btn btn-secondary mt-3">Back to Subscriptions</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
