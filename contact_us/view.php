<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");


include "../functions.php";


$input = file_get_contents('php://input');
$data = json_decode($input, true) ?: $_POST;

$userId = isset($dataInput['userId']) ? intval($dataInput['userId']) : 0;



if ($userId == 0) {
    echo json_encode(["status" => "failed", "message" => "user id is required"]);
    exit();
}



getAllData('contact_us', "contact_us_users_id = $userId");
