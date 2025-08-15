<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include "../connect.php";

$input = file_get_contents('php://input');
$data = json_decode($input, true) ?: $_POST;

$addressId = isset($data['addressId']) ? intval($data['addressId']) : 0;

if ($addressId <= 0) {
    echo json_encode([
        "status" => "failure",
        "message" => "addressId is required and must be greater than 0"
    ]);
    exit();
}

$stmt = $connect->prepare("DELETE FROM `address` WHERE address_id = ?");
$stmt->execute([$addressId]);

if ($stmt->rowCount() > 0) {
    echo json_encode([
        "status" => "success",
        "message" => "Address deleted successfully"
    ]);
} else {
    echo json_encode([
        "status" => "failure",
        "message" => "No address found with this ID"
    ]);
}
