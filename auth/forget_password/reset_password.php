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
$password = isset($data['password']) ? $data['password'] : '';

// التحقق من القيم المطلوبة
if (empty($email) || empty($password)) {
    echo json_encode(["status" => "failed", "message" => "All fields are required"]);
    exit();
}

// التحقق من صحة البريد الإلكتروني
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "failed", "message" => "Invalid email format"]);
    exit();
}

try {
    // التحقق من وجود المستخدم بالبريد الإلكتروني
    $stmt = $connect->prepare('SELECT * FROM `users` WHERE `users_email` = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // تشفير كلمة المرور الجديدة
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // تنفيذ التحديث
        $stmt = $connect->prepare("UPDATE `users` SET `users_password` = ? WHERE `users_email` = ?");
        $stmt->execute([$hashed_password, $email]);

        echo json_encode([
            "status" => "success",
            "message" => "Password updated successfully"
        ]);
    } else {
        echo json_encode(["status" => "failed", "message" => "Email not found"]);
    }

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
