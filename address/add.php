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
$city = isset($data['city']) ? trim($data['city']) : '';
$street = isset($data['street']) ? trim($data['street']) : '';
$lat = isset($data['lat']) ? floatval($data['lat']) : 0;
$lang = isset($data['lang']) ? floatval($data['lang']) : 0;

if ($userId <= 0 || $city === '' || $street === '' || $lat === '' || $lang === '') {
    echo json_encode([
        "status" => "failure",
        "message" => "element are required"
    ]);
    exit;
}

$data = array(
    "address_users_id" => $userId,
    "address_city" => $city,
    "address_street" => $street,
    "address_lat" => $lat,
    "address_lang" => $lang,
);

insertData("address", $data);
