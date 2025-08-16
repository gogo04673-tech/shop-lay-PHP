<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include "../functions.php";

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

// حط القيم بين '' لحمايتها
$where = "coupon_name = '$couponName' AND coupon_expire_date > '$now' AND coupon_count > 0";

// استدعاء الدالة
getData("coupon", $where);
