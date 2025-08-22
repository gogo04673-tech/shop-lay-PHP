<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include "../connect.php";

$input = file_get_contents('php://input');
$data = json_decode($input, true) ?: $_POST;

$orderId = isset($data['orderId']) ? intval($data['orderId']) : 0;

if ($orderId <= 0) {
    echo json_encode([
        "status" => "failure",
        "message" => "addressId is required and must be greater than 0"
    ]);
    exit();
}

$stmt = $connect->prepare("DELETE FROM `orders` WHERE orders_id = ? AND orders_status = 0");
$stmt->execute([$orderId]);

if ($stmt->rowCount() > 0) {
    echo json_encode([
        "status" => "success",
        "message" => "Order deleted successfully"
    ]);
} else {
    echo json_encode([
        "status" => "failure",
        "message" => "No order found with this ID"
    ]);
}
