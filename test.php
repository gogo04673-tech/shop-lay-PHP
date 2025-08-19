<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include "./functions.php";

echo sendGCM("🔔 إشعار تجريبي", "أهلا محمد، هذا إشعار من FCM v1 API", "news", "123", "homepage");
