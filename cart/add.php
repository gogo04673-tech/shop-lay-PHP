<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include "../functions.php";

$input = file_get_contents('php://input');
$data = json_decode($input, true) ?: $_POST;

$userId = isset($data['userId']) ? intval($data['userId']) : 0;
$itemId = isset($data['itemId']) ? intval($data['itemId']) : 0;

if (empty($userId) || empty($itemId)) {
    echo json_encode([
        "status" => "failure",
        "message" => "userId and itemId are required"
    ]);
    exit;
}

// تحقق أن المنتج موجود
$itemExists = getData("items", "items_id = $itemId", false);
if ($itemExists == 0) {
    echo json_encode([
        "status" => "failure",
        "message" => "Item does not exist"
    ]);
    exit;
}

// تحقق أن المنتج غير مكرر في السلة
$count = getData("cart", "cart_items_id = $itemId AND cart_users_id = $userId", false);


// إضافة المنتج للسلة
$data = array(
    "cart_users_id" => $userId,
    "cart_items_id" => $itemId
);

insertData("cart", $data);
