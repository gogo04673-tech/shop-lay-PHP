<?php
include "./functions.php";
$allData = array();

$categories = getData("categories", false);


$allData['success'] = "success";

$allData['categories'] = $categories;

echo $allData;
