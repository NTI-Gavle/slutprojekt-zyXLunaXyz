<?php
require_once __DIR__ . '/../config/env.php';

// Load the .env file
$env = loadEnv(__DIR__ . '/../.env');


$dbname = $env['DB_NAME'] ?? 'z_social';
$hostname = $env['DB_HOST'] ?? 'localhost';
$DB_USER = $env['DB_USER'] ?? 'root';
$DB_PASSWORD = $env['DB_PASS']?? 'root';

try {
    $dbconn = new PDO(
        "mysql:host=$hostname;dbname=$dbname;charset=utf8mb4",
        $DB_USER,
        $DB_PASSWORD
    );
    
    $dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbconn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}
catch(PDOException $e){
    echo 'Connection failed: ' . $e->getMessage();
}
