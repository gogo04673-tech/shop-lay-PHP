<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include "../connect.php";

// استقبال البيانات سواء من POST أو JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true) ?: $_POST;

$username = isset($data['username']) ? $data['username'] : '';
$email = isset($data['email']) ? $data['email'] : '';
$phone = isset($data['phone']) ? $data['phone'] : '';
$verify_code = rand(10000, 99999);
$password = isset($data['password']) ? $data['password'] : '';

if (empty($email) || empty($password) || empty($username) || empty($phone)) {
    echo json_encode(["status" => "failed", "message" => "Email and password are required"]);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "failed", "message" => "Invalid email format"]);
    exit();
}

try {
    // التحقق من وجود البريد الإلكتروني
    $stmt = $connect->prepare('SELECT * FROM `users` WHERE `users_email` = ? OR `users_phone` = ? ');
    $stmt->execute([$email, $phone]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode(["status" => "failed", "message" => "Email or Phone already exists"]);
        exit();
    }

    // تسجيل المستخدم الجديد
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $connect->prepare("INSERT INTO `users`(`users_name`, `users_email`, `users_phone`, `users_verifycode`, `users_password`) VALUES (?, ?, ?, ?, ?)");

    $stmt->execute([$username, $email, $phone, $verify_code, $hashed_password]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $count = $stmt->rowCount();
    if ($count > 0) {
        //send_verification_code($email, "eljihadmohammed84@gmail.com", $verify_code);
        echo json_encode(["status" => "success", "message" => "Account created successfully", "data"=> $users]);
    } else {
        echo json_encode(["status" => "failed", "message" => "Error creating account"]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "failed", "message" => "Database error: " . $e->getMessage()]);
}
