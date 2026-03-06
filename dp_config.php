<?php
$host = 'localhost';
$db_name = 'rsoa_rsoa285_34';
$username = 'rsoa_rsoa285_34';
$password = '123456';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // For local testing if the specific database doesn't exist yet
    // die("Connection failed: " . $e->getMessage());
    
    // Attempting a fallback for local development if needed
    try {
        $conn = new PDO("mysql:host=localhost", "root", "");
        $conn->exec("CREATE DATABASE IF NOT EXISTS $db_name");
        $conn->exec("USE $db_name");
    } catch(PDOException $ex) {
        // die("Local connection failed: " . $ex->getMessage());
    }
}
?>
