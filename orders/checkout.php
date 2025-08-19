<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include "../functions.php";
include "../connect.php";

$input = file_get_contents('php://input');
$data = json_decode($input, true) ?: $_POST;

$userId = isset($data['userId']) ? intval($data['userId']) : 0;
$ordersPaymentMethod = isset($data['ordersPaymentMethod']) ? intval($data['ordersPaymentMethod']) : 0;
$ordersAddress = isset($data['ordersAddress']) ? intval($data['ordersAddress']) : 0;
$ordersType = isset($data['ordersType']) ? intval($data['ordersType']) : 0;
$ordersPriceDelivery = isset($data['ordersPriceDelivery']) ? floatval($data['ordersPriceDelivery']) : 0;
$ordersPrice = isset($data['ordersPrice']) ? floatval($data['ordersPrice']) : 0;
$ordersCoupon = isset($data['ordersCoupon']) ? intval($data['ordersCoupon']) : 0;
$ordersCouponDiscount = isset($data['ordersCouponDiscount']) ? intval($data['ordersCouponDiscount']) : 0;
$now = date("Y-m-d H:i:s");

$totalPrice = $ordersPrice + $ordersPriceDelivery;


if (empty($userId)) {
    echo json_encode([
        "status" => "failure",
        "message" => "userId and itemId are required"
    ]);
    exit;
}



// استعلام باستخدام Prepared Statement
$stm = $connect->prepare('SELECT * FROM `coupon` WHERE `coupon_id` = ? AND `coupon_expire_date` > ? AND `coupon_count` > 0');
$stm->execute([$ordersCoupon, $now]);
$coupon = $stm->rowCount();

if ($coupon > 0) {
    $totalPrice = $totalPrice - $ordersPrice * $ordersCouponDiscount / 100;
}



$data = array(
    "orders_users_id" => $userId,
    "orders_payment_method" => $ordersPaymentMethod,
    "orders_address" => $ordersAddress,
    "orders_type" => $ordersType,
    "orders_price_delivery" => $ordersPriceDelivery,
    "orders_price" => $ordersPrice,
    "orders_total_price" => $totalPrice,
    "orders_coupon" => $ordersCoupon,
    "orders_coupon_discount" => $ordersCouponDiscount
);


try {
    $count = insertData('orders', $data, false);

    if ($count > 0) {
        $stmt = $connect->prepare("SELECT MAX(orders_id) FROM `orders`");
        $stmt->execute();
        $maxId = $stmt->fetchColumn();

        $stmt1 = $connect->prepare("UPDATE `cart` SET `cart_orders` = ? WHERE `cart_users_id` = ? AND `cart_orders` = 0");
        $stmt1->execute(array($maxId, $userId));

        if ($stmt1->rowCount() > 0) {
            echo json_encode(["status" => $count > 0 ? "success" : "failure"]);
        }
    }
} catch (PDOException $e) {
    if ($json) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    } else {
        throw $e;
    }
}
