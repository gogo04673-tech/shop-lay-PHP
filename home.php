<?php
include "./functions.php";

$allData = [];
$categories = getData("categories", false);

$allData['success'] = "success";
$allData['categories'] = $categories;

echo json_encode($allData);
