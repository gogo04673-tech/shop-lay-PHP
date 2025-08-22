<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include "../connect.php";


$sql = "
    SELECT items_view.*, 1 as favorite, 
    (items_price - (items_price * items_discount / 100)) as items_price_discount
    FROM items_view 
    INNER JOIN favorite 
        ON favorite.favorite_item_id = items_view.items_id 
        
    WHERE items_discount != 0 

    UNION ALL

    SELECT items_view.*, 0 as favorite,
    (items_price - (items_price * items_discount / 100)) as items_price_discount 
    FROM items_view
    WHERE items_discount != 0
      AND items_id NOT IN ( 
            SELECT items_view.items_id 
            FROM items_view 
            INNER JOIN favorite 
                ON favorite.favorite_item_id = items_view.items_id 
                
      )
";

$stmt = $connect->prepare($sql);

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
