<?php
include "./functions.php";

$allData = [];

$items = getData("items", null, false);


$allData['status'] = "success";
$allData['items'] = $items;

echo json_encode($allData);
