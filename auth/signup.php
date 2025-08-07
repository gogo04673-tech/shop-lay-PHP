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
$password = isset($data['password']) ? $data['password'] : '';
$verify_code = rand(10000, 99999);

// التحقق من القيم المطلوبة
if (empty($email) || empty($password) || empty($username) || empty($phone)) {
    echo json_encode(["status" => "failed", "message" => "All fields are required"]);
    exit();
}

// التحقق من صحة البريد الإلكتروني
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "failed", "message" => "Invalid email format"]);
    exit();
}

try {
    // التحقق من وجود البريد الإلكتروني أو رقم الهاتف مسبقًا
    $stmt = $connect->prepare('SELECT * FROM `users` WHERE `users_email` = ? OR `users_phone` = ?');
    $stmt->execute([$email, $phone]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode(["status" => "failed", "message" => "Email or phone already exists"]);
        exit();
    }

    // تشفير كلمة المرور
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // إدخال المستخدم الجديد
    $stmt = $connect->prepare("INSERT INTO `users`(`users_name`, `users_email`, `users_phone`, `users_verifycode`, `users_password`) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$username, $email, $phone, $verify_code, $hashed_password]);

    // التحقق من نجاح الإدخال
    $count = $stmt->rowCount();
    if ($count > 0) {
        // الحصول على آخر ID تم إدخاله
        $userId = $connect->lastInsertId();

        // جلب بيانات المستخدم الجديد
        $stmtUser = $connect->prepare("SELECT `users_id`, `users_name`, `users_email`, `users_phone`, `users_verifycode` FROM `users` WHERE `users_id` = ?");
        $stmtUser->execute([$userId]);
        $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            "status" => "success",
            "message" => "Account created successfully",
            "data" => $userData
        ]);
    } else {
        echo json_encode(["status" => "failed", "message" => "Error creating account"]);
    }

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
