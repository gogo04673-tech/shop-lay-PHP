<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

// include "../functions.php";
include "../connect.php";

$input = file_get_contents('php://input');
$dataInput = json_decode($input, true) ?: $_POST;

$search = isset($dataInput['search']) ? trim($dataInput['search']) : '';

if ($search === '') {
    echo json_encode([
        "status" => "failure",
        "message" => "search is required"
    ]);
    exit;
}


$where = "items_name LIKE :search OR items_name_ar LIKE :search";



try {
    $sql = "SELECT * FROM items_view WHERE $where";
    $stmt = $connect->prepare($sql);
    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => count($data) > 0 ? "success" : "failed",
        "message" => count($data) > 0 ? "Data retrieved successfully" : "No data found",
        "data" => $data
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "failed",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
