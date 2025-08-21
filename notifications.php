<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");


include "./functions.php";

$userId  = isset($data['userId']) ? intval($data['userId']) : 0;

if ($userId == 0) {
    echo array(
        "status" => "Failure",
        "message" => "User id is required"
    );
    exit();
}

getAllData('notifications', "notifications_users_id = $userId ORDER BY $userId DESC");
