<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include "../../connect.php";

// استقبال البيانات سواء من POST أو JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true) ?: $_POST;


$email = isset($data['email']) ? $data['email'] : '';
$verify_code = isset($data['verifyCode']) ? $data['verifyCode'] : '';


if (empty($email)) {
    echo json_encode(["status" => "failed", "message" => "Email and password are required"]);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "failed", "message" => "Invalid email format"]);
    exit();
}

try {
    // التحقق من وجود البريد الإلكتروني
    $stmt = $connect->prepare('SELECT * FROM `users` WHERE `users_email` = ? AND `users_verifycode` = ? ');
    $stmt->execute([$email, $verify_code]);


    $count = $stmt->rowCount();
    if ($count > 0) {
        echo json_encode(["status" => "success", "message" => "Account verify successfully"]);
    } else {
        echo json_encode(["status" => "failed", "message" => "Error verify account"]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "failed", "message" => "Database error: " . $e->getMessage()]);
}
