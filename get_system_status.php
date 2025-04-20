<?php
header('Content-Type: application/json');

// Simple static or simulated data
echo json_encode([
    'cpu_usage' => rand(20, 60),           // Simulated CPU % usage
    'memory_usage' => rand(30, 70),        // Simulated Memory % usage
    'disk_usage' => rand(10, 50),          // Simulated Disk % usage
    'active_users' => rand(1, 5),          // Simulated user sessions
    'db_status' => 'Connected'             // Simulated DB status
]);
?>
