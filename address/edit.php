<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include "../connect.php";

$input = file_get_contents('php://input');
$data = json_decode($input, true) ?: $_POST;

$addressId = isset($data['addressId']) ? intval($data['addressId']) : 0;
$city = isset($data['city']) ? trim($data['city']) : '';
$street = isset($data['street']) ? trim($data['street']) : '';
$lat = isset($data['lat']) ? floatval($data['lat']) : 0;
$lang = isset($data['lang']) ? floatval($data['lang']) : 0;

if ($addressId <= 0 || $city === '' || $street === '' || !is_numeric($lat) || !is_numeric($lang)) {
    echo json_encode([
        "status" => "failure",
        "message" => "All elements are required"
    ]);
    exit;
}

try {
    $stmt = $connect->prepare('SELECT * FROM `address` WHERE `address_id` = ?');
    $stmt->execute([$addressId]);
    $address = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($address) {
        $st = $connect->prepare("UPDATE `address` SET `address_city` = ?, `address_street` = ?, `address_lat` = ?, `address_lang` = ? WHERE `address_id` = ?");
        $st->execute([$city, $street, $lat, $lang, $addressId]);

        echo json_encode([
            "status" => "success",
            "message" => "Update is successful"
        ]);
    } else {
        echo json_encode([
            "status" => "failed",
            "message" => "Address not found"
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
