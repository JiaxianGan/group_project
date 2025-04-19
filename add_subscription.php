<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}



// Handle adding a new subscription
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_subscription'])) {
    $plan_name = $_POST['plan_name'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    

    $vendor_id = isset($_SESSION['vendor_id']) ? $_SESSION['vendor_id'] : 1;
    $insert_query = "INSERT INTO subscriptions (plan_name, price, duration, vendor_id) VALUES (?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("sdii", $plan_name, $price, $duration, $vendor_id);
    $insert_stmt->execute();
   

    header("Location: vendor_subscriptions.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Subscription Plan</title>
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
        <h1>Add New Subscription Plan</h1>
        <form method="POST" action="add_subscription.php">
            <div class="mb-3">
                <label for="plan_name" class="form-label">Plan Name</label>
                <select class="form-select" id="plan_name" name="plan_name" required>
                    <option value="" disabled selected>Select a plan</option>
                    <option value="Basic">Basic</option>
                    <option value="Premium">Premium</option>
                    <option value="Enterprise">Enterprise</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" required>
            </div>
            <div class="mb-3">
                <label for="duration" class="form-label">Duration (Months)</label>
                <select class="form-select" id="duration" name="duration" required>
                    <option value="" disabled selected>Select duration</option>
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?> Month<?php echo $i > 1 ? 's' : ''; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <button type="submit" name="add_subscription" class="btn btn-primary">Add Subscription</button>
        </form>
        <a href="vendor_subscriptions.php" class="btn btn-secondary mt-3">Back to Subscriptions</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
