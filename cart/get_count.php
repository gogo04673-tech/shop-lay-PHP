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
SELECT COUNT(cart.cart_id) as count_item 
FROM cart  
WHERE cart_items_id = ? AND cart_users_id = ?
";
$stmt = $connect->prepare($sql);
$stmt->execute([$itemId, $userId]);
$data = $stmt->fetchColumn();

if ($stmt->rowCount() > 0) {
    echo json_encode([
        "status" => "success",
        "message" => "Item count from cart",
        "data" => $data
    ]);
} else {
    echo json_encode([
        "status" => "failure",
        "message" => "Item not found in cart"
    ]);
}
