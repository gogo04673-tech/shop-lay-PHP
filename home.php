<?php
include "./functions.php";

$allData = [];
$categories = getData("categories", null, false);
$items = getData("items", null, false);
$itemView = getData("items_view", "items_discount != 0", false);

$allData['status'] = "success";
$allData['categories'] = $categories;
$allData['itemView'] = $itemView;
$allData['items'] = $items;

echo json_encode($allData);
