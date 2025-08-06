<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, Access-Control-Allow-Origin");
header("Access-Control-Allow-Methods: POST, OPTIONS , GET");

}
$host = "mainline.proxy.rlwy.net";
$port = 55258;
$user = "root"; // أو اسم المستخدم الذي يظهر لك في Railway
$password = "PrADZrqNqKkIiuzhlKGCRaEKiMGTsKnz"; // كلمة السر من Railway
$dbname = "railway"; // اسم قاعدة البيانات

try {
    $connect = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "✅ Connected successfully to Railway DB!";
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage();
}
