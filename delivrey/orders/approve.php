<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include "../../connect.php";
include "../../functions.php";

// استلام البيانات من JSON أو POST
$input = file_get_contents('php://input');
$data = json_decode($input, true) ?: $_POST;

$userId  = isset($data['userId']) ? intval($data['userId']) : 0;
$orderId = isset($data['orderId']) ? intval($data['orderId']) : 0;
$deliveryId = isset($data['deliveryId']) ? intval($data['deliveryId']) : 0;

if ($orderId > 0 && $userId > 0) {

    // تحديث حالة الطلب
    $updateData = [
        "orders_status" => 3,
        "orders_delivery" => $deliveryId
    ];



    updateData('orders', $updateData, ["orders_id" => $orderId, "orders_status" => 2]);


    // sendGCM("Success", "Your order is preparing.", "users$userId", "none", "refreshPageOrders");
    insertNotify("Success", "Your orders is on the way", $userId, "users$userId", "none", "refreshPageOrders");


    sendGCM("Shop-Lay-Service", "The order has been Approval by delivery", "service", "none", "none");

    sendGCM("Shop-Lay-Delivery", "The order has been Approved by delivery " . $deliveryId, "delivery", "none", "none");
} else {
    echo json_encode(["status" => "error", "message" => "Missing userId or orderId"]);
}
