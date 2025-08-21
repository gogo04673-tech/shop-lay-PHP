<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include "../../connect.php";
include "../../functions.php";

$userId = isset($data['userId']) ? intval($data['userId']) : 0;
$orderId = isset($data['orderId']) ? intval($data['orderId']) : 0;

$data = array(
    "orders_status" => 1,
);

updateData('orders', $data, ["orders_id" => $orderId, "orders_status" => 0]);



echo sendGCM("Success", "Your order is preparing.", "users$userId", "none", "none");
