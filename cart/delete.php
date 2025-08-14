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

if (empty($userId) || empty($itemId)) {
    echo json_encode([
        "status" => "failure",
        "message" => "userId and itemId are required"
    ]);
    exit();
}

$sql = "
DELETE c
FROM cart c
JOIN (
    SELECT cart_id FROM cart WHERE cart_users_id = ? AND cart_items_id = ? LIMIT 1
) AS sub ON c.cart_id = sub.cart_id
";
$stmt = $connect->prepare($sql);
$stmt->execute([$userId, $itemId]);

if ($stmt->rowCount() > 0) {
    echo json_encode([
        "status" => "success",
        "message" => "Item removed from cart"
    ]);
} else {
    echo json_encode([
        "status" => "failure",
        "message" => "Item not found in cart"
    ]);
}
