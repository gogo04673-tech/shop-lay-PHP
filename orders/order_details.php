<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include "./functions.php";

$input = file_get_contents('php://input');
$dataInput = json_decode($input, true) ?: $_POST;


$cartOrders = isset($dataInput['cartOrders']) ? intval($dataInput['cartOrders']) : 0;

if ($cartOrders == 0) {
    echo json_encode([
        "status" => "Failure",
        "message" => "cart Orders  is required"
    ]);
    exit();
}

getAllData('orders_details_view', "cart_orders = $cartOrders");
