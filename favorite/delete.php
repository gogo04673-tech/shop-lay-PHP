<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include "../connect.php";

$input = file_get_contents('php://input');
$data = json_decode($input, true) ?: $_POST;

$favoriteId = isset($data['favoriteId']) ? intval($data['favoriteId']) : 0;


if (empty($favoriteId)) {
    echo json_encode([
        "status" => "failure",
        "message" => "favoriteId is required"
    ]);
    exit();
}

$stmt = $connect->prepare("DELETE FROM favorite WHERE favorite_id = ? ");
$stmt->execute([$favoriteId]);

if ($stmt->rowCount() > 0) {
    echo json_encode([
        "status" => "success",
        "message" => "Delete a favorite item"
    ]);
} else {
    echo json_encode([
        "status" => "failure",
        "message" => "Is not Delete a favorite item"
    ]);
}
