<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include "../functions.php";
include "../connect.php"; // لو محتاج الاتصال بقاعدة البيانات

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

$search = mysqli_real_escape_string($conn, $search);

getAllData(
    'items',
    "items_name LIKE '%$search%' OR items_name_ar LIKE '%$search%'"
);
