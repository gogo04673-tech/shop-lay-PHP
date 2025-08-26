<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");


include "../../functions.php";

// استلام البيانات من JSON أو POST
$input = file_get_contents('php://input');
$data = json_decode($input, true) ?: $_POST;

$deliveryId  = isset($data['deliveryId']) ? intval($data['deliveryId']) : 0;

getAllData("orders_view", "orders_status = 4 AND orders_delivery = $deliveryId");
