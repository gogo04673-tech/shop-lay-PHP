<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include "../connect.php";
include "../functions.php";

$input = file_get_contents('php://input');
$dataInput = json_decode($input, true) ?: $_POST;


$userId = isset($dataInput['userId']) ? intval($dataInput['userId']) : 0;

if (empty($userId)) {
    echo json_encode([
        "status" => "failure",
        "message" => "userId is required"
    ]);
    exit;
}


getAllData('orders', "orders_users_id = $userId");

