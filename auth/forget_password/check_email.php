<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include "./connect.php";

// استقبال البيانات سواء من POST أو JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true) ?: $_POST;

$email = isset($data['email']) ? $data['email'] : '';
$verifyCode = rand(10000, 99999);

// التحقق من القيم المطلوبة
if (empty($email)) {
    echo json_encode(["status" => "failed", "message" => "All fields are required"]);
    exit();
}

// التحقق من صحة البريد الإلكتروني
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "failed", "message" => "Invalid email format"]);
    exit();
}

try {
    // التحقق من وجود البريد الإلكتروني مسبقًا
    $stmt = $connect->prepare('SELECT * FROM `users` WHERE `users_email` = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $st = $connect->prepare("UPDATE `users` SET `users_verifycode` = ? WHERE `users_email`= ?");
        $st->execute([$verifyCode, $email]);;
        echo json_encode([
            "status" => "success",
            "message" => "Account is found."
        ]);
    } else {
        echo json_encode(["status" => "failed", "message" => "Email is not found."]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
