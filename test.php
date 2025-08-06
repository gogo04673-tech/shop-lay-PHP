<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include "./connect.php";

try {
    // جلب كل المستخدمين من جدول users
    $stmt = $connect->prepare('SELECT * FROM `users`');
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($users) > 0) {
        echo json_encode([
            "status" => "success",
            "message" => "Data retrieved successfully",
            "data" => $users
        ]);
    } else {
        echo json_encode([
            "status" => "failed",
            "message" => "No users found"
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "status" => "failed",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
