<?php
include "./functions.php";

$allData = [];
$categories = getData("categories", false);

$allData['status'] = "success";
$allData['categories'] = $categories;

echo json_encode($allData);
