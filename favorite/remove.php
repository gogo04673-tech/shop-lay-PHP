<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include "../connect.php";

$input = file_get_contents('php://input');
$data = json_decode($input, true) ?: $_POST;

$userId = isset($data['userId']) ? intval($data['userId']) : 0;
$itemId = isset($data['itemId']) ? intval($data['itemId']) : 0;

// التحقق من القيم المطلوبة
if (empty($userId) || empty($itemId)) {
    echo json_encode([
        "status" => "failure",
        "message" => "userId and itemId are required"
    ]);
    exit();
}

$stmt = $connect->prepare("DELETE FROM favorite WHERE favorite_item_id = ? AND favorite_user_id = ?");
$stmt->execute([$itemId, $userId]);

if ($stmt->rowCount() > 0) {
    echo json_encode([
        "status" => "success",
        "message" => "Item removed from favorites"
    ]);
} else {
    echo json_encode([
        "status" => "failure",
        "message" => "Item not found in favorites"
    ]);
}
