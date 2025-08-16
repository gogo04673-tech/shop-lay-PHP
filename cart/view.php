<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include "../functions.php";
include "../connect.php";

$input = file_get_contents('php://input');
$data = json_decode($input, true) ?: $_POST;

$userId = isset($data['userId']) ? intval($data['userId']) : 0;

if ($userId <= 0) {
    echo json_encode([
        "status" => "failure",
        "message" => "userId is required and must be greater than 0"
    ]);
    exit;
}

getAllData("cart", "cart_users_id = $userId");
