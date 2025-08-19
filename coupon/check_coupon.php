<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include "../connect.php";

$input = file_get_contents('php://input');
$data = json_decode($input, true) ?: $_POST;

$couponName = isset($data['couponName']) ? trim($data['couponName']) : '';
$now = date("Y-m-d H:i:s");

if ($couponName === '') {
    echo json_encode([
        "status" => "failure",
        "message" => "couponName is required"
    ]);
    exit;
}

// استعلام باستخدام Prepared Statement
$stmt = $connect->prepare('SELECT * FROM `coupon` WHERE `coupon_name` = ? AND `coupon_expire_date` > ? AND `coupon_count` > 0');
$stmt->execute([$couponName, $now]);
$coupon = $stmt->fetch(PDO::FETCH_ASSOC);

if ($stmt->rowCount() == 0) {
    echo json_encode([
        "status" => "failed",
        "message" => "Coupon not found or expired or count is 0"
    ]);
    exit();
}

// لو وصل هنا معناها الكوبون صالح
echo json_encode([
    "status" => "success",
    "message" => "Coupon is valid",
    "data"    => $coupon
]);
