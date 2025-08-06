<?php
$host = "mainline.proxy.rlwy.net";
$port = 3306;
$user = "root"; // أو حسب ما يظهر لك
$password = "PrADZrqNqKkIiuzhlKGCRaEKiMGTsKnz";
$dbname = "railway";

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connected successfully to Railway MySQL";
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage();
}
