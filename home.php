<?php
include "./functions.php";

$allData = [];
$categories = getAllData("categories", null, false);
$items = getAllData("items", null, false);
$itemView = getAllData("items_view", "items_discount != 0", false);

$allData['status'] = "success";
$allData['categories'] = $categories;
$allData['itemView'] = $itemView;
$allData['items'] = $items;

echo json_encode($allData);
