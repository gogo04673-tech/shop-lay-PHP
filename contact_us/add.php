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
$title = isset($data['title']) ? $data['title'] : '';
$body = isset($data['body']) ? $data['body'] : '';


if ($userId == 0 || empty($title) || empty($body)) {
    echo json_encode(["status" => "failed", "message" => "All fields are required"]);
    exit();
}

$data = array(
    "contact_us_users_id" => $userId,
    "contact_us_title" => $title,
    "contact_us_body" => $body
);

insertData('contact_us', $data);
