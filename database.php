<?php

$hostName = "localhost";
$dbUser = "root";  // Should be changed in production
$dbPassword = "";   // Should be changed in production
$dbName = "login_registerr";

// Enable error reporting for debugging (disable in production)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
    // Set charset to ensure proper encoding
    mysqli_set_charset($conn, 'utf8mb4');
} catch (Exception $e) {
    error_log("Connection failed: " . $e->getMessage());
    die("Connection error: Please try again later.");
}

?>