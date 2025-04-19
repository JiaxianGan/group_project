<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: auth.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['staff_add'])) {
    // Get form values
    $username = $_POST['username'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $contact = $_POST['contact'];

    // Get vendor_id from session
    $vendor_id = isset($_SESSION['vendor_id']) ? $_SESSION['vendor_id'] : 1;

    // Prepare the SQL statement
    $insert_query = "INSERT INTO staff (username, email, full_name, contact, vendor_id) VALUES (?, ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    
    // Bind parameters (s = string, i = integer)
    $insert_stmt->bind_param("ssssi", $username, $email, $full_name, $contact, $vendor_id);
    
    // Execute the statement
    if ($insert_stmt->execute()) {
        // Redirect to vendor_staff.php after successful insertion
        header("Location: vendor_staff.php");
        exit();
    } else {
        echo "Error: " . $insert_stmt->error; // Display error if any
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Staff</title>
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
    <h2>Add New Staff</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name" required>
        </div>
        <div class="mb-3">
            <label for="contact" class="form-label">Contact</label>
            <input type="text" class="form-control" id="contact" name="contact" required>
        </div>
        <button type="submit" name="staff_add" class="btn btn-primary">Add Staff</button>
    </form>
</div>
</body>
</html>
