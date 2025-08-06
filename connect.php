<?php
$host = "mainline.proxy.rlwy.net";
$port = 55258;
$user = "root"; // أو اسم المستخدم الذي يظهر لك في Railway
$password = "PrADZrqNqKkIiuzhlKGCRaEKiMGTsKnz"; // كلمة السر من Railway
$dbname = "railway"; // اسم قاعدة البيانات

try {
    $connect = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connected successfully to Railway DB!";
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage();
}
