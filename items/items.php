<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include "../connect.php";

$input = file_get_contents('php://input');
$dataInput = json_decode($input, true) ?: $_POST;

$categoryId = isset($dataInput['categoryId']) ? intval($dataInput['categoryId']) : 0;
$userId = isset($dataInput['userId']) ? intval($dataInput['userId']) : 0;

if (empty($categoryId) || empty($userId)) {
    echo json_encode([
        "status" => "failure",
        "message" => "categoryId AND userId are required"
    ]);
    exit;
}

$sql = "
    SELECT items_view.*, 1 as favorite 
    FROM items_view 
    INNER JOIN favorite 
        ON favorite.favorite_item_id = items_view.items_id 
        AND favorite.favorite_user_id = :userId
    WHERE categories_id = :categoryId

    UNION ALL

    SELECT items_view.*, 0 as favorite 
    FROM items_view
    WHERE categories_id = :categoryId 
      AND items_id NOT IN ( 
            SELECT items_view.items_id 
            FROM items_view 
            INNER JOIN favorite 
                ON favorite.favorite_item_id = items_view.items_id 
                AND favorite.favorite_user_id = :userId
      )
";

$stmt = $connect->prepare($sql);
$stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
$stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);

$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($result) {
    echo json_encode([
        "status" => "success",
        "message" => "Items retrieved successfully",
        "data" => $result
    ]);
} else {
    echo json_encode([
        "status" => "failure",
        "message" => "No items found",
    ]);
}
