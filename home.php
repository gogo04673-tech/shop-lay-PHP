<?php
include "./functions.php";

$allData = [];
$categories = getAllData("categories", null, false);
$items = getAllData("items", null, false);
$items_top_seller = getAllData("items_top_seller", "1 = 1 ORDER BY countItems DESC", false);

$allData['status'] = "success";
$allData['categories'] = $categories;
$allData['items_top_seller'] = $items_top_seller;
$allData['items'] = $items;

echo json_encode($allData);
