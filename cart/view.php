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


if (empty($userId)) {
    echo json_encode([
        "status" => "failure",
        "message" => "userId are required"
    ]);
    exit;
}

$data = getAllData("items_cart", "null", false);

$sql = "
SELECT 
SUM(items_cart.total) as total_price_items, 
COUNT(items_cart.count_item) as total_count_items 
FROM `items_cart` 
WHERE items_cart.cart_users_id = ? 
GROUP BY cart_users_id
";
$stmt = $connect->prepare($sql);
$stmt->execute([$userId]);
$dataCountAndPrice = $stmt->fetch(PDO::FETCH_ASSOC);

if ($stmt->rowCount() > 0) {
    echo json_encode([
        "status" => "success",
        "message" => "Items cart and data count and price are found.",
        "data" => $data,
        "countPrice" => $dataCountAndPrice
    ]);
} else {
    echo json_encode([
        "status" => "failure",
        "message" => "Items cart and data count and price aren't found."
    ]);
}
