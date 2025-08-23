<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include "../connect.php";
include "../functions.php";

$input = file_get_contents('php://input');
$dataInput = json_decode($input, true) ?: $_POST;


$orderId = isset($dataInput['orderId']) ? intval($dataInput['orderId']) : 0;
$ordersRating = isset($dataInput['ordersRating']) ? intval($dataInput['ordersRating']) : 0;
$ordersRatingCommit = isset($dataInput['ordersRatingCommit']) ? $dataInput['ordersRatingCommit'] : '';

$data = array(
    "orders_rating" => $ordersRating,
    "orders_rating_commit" => $ordersRatingCommit

);

updateData("orders", $data, "orders_id = $orderId");
