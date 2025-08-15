<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include "../functions.php";

$input = file_get_contents('php://input');
$dataInput = json_decode($input, true) ?: $_POST;

$search = isset($dataInput['search']) ? intval($dataInput['search']) : 0;

// if (empty($search)) {
//     echo json_encode([
//         "status" => "failure",
//         "message" => "search is required"
//     ]);
//     exit;
// }

getAllData('items', "items_name LIKE `%$search%` OR items_name_ar LIKE `%$search%`");
