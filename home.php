<?php
include "./functions.php";

$allData = [];
$categories = getData("categories", null, false);
$itemView = getData("items_view", "items_discount != 0", false);

$allData['status'] = "success";
//$allData['categories'] = $categories;
$allData['itemView'] = $itemView;

echo json_encode($allData);
