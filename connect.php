<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, Access-Control-Allow-Origin");
header("Access-Control-Allow-Methods: POST, OPTIONS , GET");

include_once __DIR__ . "/functions.php";

if (!function_exists('filterRequest')) {
    function filterRequest($requestName)
    {
        return htmlspecialchars(strip_tags($_POST[$requestName]));
    }
}

// connect.php ecommerce
$host = "sql308.infinityfree.com";     // ← المضيف الصحيح
$user = "if0_39641050";                // ← اسم المستخدم من CPanel
$pass = "codeShoplay21";               // ← كلمة المرور الصحيحة
$db   = "if0_39641050_ecommerce";      // ← اسم قاعدة البيانات

try {
    $connect = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode([
        "status" => "failed",
        "message" => "Connection failed: " . $e->getMessage()
    ]));
}
