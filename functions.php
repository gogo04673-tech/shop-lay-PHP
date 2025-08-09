<?php


require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

// استيراد الـ namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_verification_code($toEmail, $userName, $verificationCode)
{
	$mail = new PHPMailer(true);

	try {
		// إعدادات SMTP
		$mail->isSMTP();
		$mail->Host       = 'smtp.gmail.com';
		$mail->SMTPAuth   = true;
		$mail->Username   = 'eljihadmohammed84@gmail.com'; // بريدك
		$mail->Password   = 'rkrg umbw xlem hjsd'; // كلمة مرور تطبيق Gmail (وليس كلمة السر العادية)
		$mail->SMTPSecure = 'tls';
		$mail->Port       = 587;

		// المرسل والمستلم
		$mail->setFrom('youremail@gmail.com', 'Your App');
		$mail->addAddress($toEmail, $userName);

		// المحتوى
		$mail->isHTML(true);
		$mail->Subject = 'Email Verification Code';
		$mail->Body    = "
            <h2>Hello, $userName</h2>
            <p>Your verification code is:</p>
            <h3>$verificationCode</h3>
        ";

		$mail->send();
		return true;
	} catch (Exception $e) {
		echo "Mailer Error: " . $mail->ErrorInfo;
		return false;
	}
}


if (!function_exists('filterRequest')) {
	function filterRequest($requestName)
	{
		return htmlspecialchars(strip_tags($_POST[$requestName]));
	}
}

function filterRequest($requestName)
{
	return htmlspecialchars(strip_tags($_POST[$requestName]));
}


function checkAuthenticate()
{
	if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
		if ($_SERVER['PHP_AUTH_USER'] != "mohamed" ||  $_SERVER['PHP_AUTH_PW'] != "mohamed1234") {
			header('WWW-Authenticate: Basic realm="My Realm"');
			header('HTTP/1.0 401 Unauthorized');
			echo 'Page Not Found';
			exit;
		}
	} else {
		exit;
	}
}


function deleteFile(string $dir, string $fileName): bool
{
	// 1. التحقق من صحة المدخلات
	if (empty($dir) || empty($fileName)) {
		throw new InvalidArgumentException("Directory and file name must be provided");
	}

	// 2. تطبيع المسارات وتجنب هجمات Directory Traversal
	$safeDir = realpath($dir);
	$safeFileName = basename($fileName);

	if (!$safeDir) {
		throw new RuntimeException("Directory does not exist or is inaccessible");
	}

	// 3. بناء المسار الآمن
	$filePath = $safeDir . DIRECTORY_SEPARATOR . $safeFileName;

	// 4. التحقق من وجود الملف
	if (!file_exists($filePath)) {
		return false; // الملف غير موجود
	}

	// 5. التحقق من أنه ملف وليس مجلد
	if (!is_file($filePath)) {
		throw new RuntimeException("Path is not a file: " . $filePath);
	}

	// 6. التحقق من الصلاحيات
	if (!is_writable($filePath)) {
		throw new RuntimeException("File is not writable: " . $filePath);
	}

	// 7. محاولة الحذف
	if (@unlink($filePath)) {
		return true;
	}

	// 8. معالجة الأخطاء
	$error = error_get_last();
	throw new RuntimeException("Failed to delete file: " . ($error['message'] ?? 'Unknown error'));
}

function secureFileUpload($requestFile)
{
	// المسار الثابت مع صلاحيات مضمونة
	$targetDir = '/opt/lampp/htdocs/flutter_login_api/upload/';

	$errors = [];

	// 1. التحقق من وجود المجلد
	if (!is_dir($targetDir)) {
		$errors[] = "folder_not_found";
		return $errors;
	}

	// 2. التحقق من إمكانية الكتابة
	if (!is_writable($targetDir)) {
		$errors[] = "folder_not_writable";
		return $errors;
	}

	// 3. التحقق من وجود الملف
	if (!isset($_FILES[$requestFile]) || $_FILES[$requestFile]['error'] !== UPLOAD_ERR_OK) {
		$errors[] = "upload_failed";
		return $errors;
	}

	$file = $_FILES[$requestFile];

	// 4. التحقق من حجم الملف (2MB كحد أقصى)
	$maxSize = 2 * 1048576;
	if ($file['size'] > $maxSize) {
		$errors[] = "size_exceeded";
	}

	// 5. توليد اسم آمن
	$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
	$allowedExt = ["jpg", "jpeg", "png", "gif", "webp"];
	$newName = uniqid() . '.' . $ext;
	$targetPath = $targetDir . $newName;

	// 6. التحقق من الامتداد
	if (!in_array($ext, $allowedExt)) {
		$errors[] = "invalid_extension";
	}

	// 7. التحقق من MIME Type
	$allowedMimes = [
		'image/jpeg',
		'image/png',
		'image/gif',
		'image/webp'
	];
	$mime = mime_content_type($file['tmp_name']);
	if (!in_array($mime, $allowedMimes)) {
		$errors[] = "invalid_mime";
	}

	// 8. محاولة رفع الملف
	if (empty($errors)) {
		if (move_uploaded_file($file['tmp_name'], $targetPath)) {
			return $newName;
		} else {
			$errors[] = "move_failed";
		}
	}

	return $errors;
}

// * get data Function 
function getData($table, $json = true)
{
	include "./connect.php";

	try {
		// جلب كل المستخدمين من جدول users
		$stmt = $connect->prepare('SELECT * FROM ?');
		$stmt->execute([$table]);
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);


		if ($json == true) {
			if (count($data) > 0) {
				echo json_encode([
					"status" => "success",
					"message" => "Data retrieved successfully",
					"data" => $data
				]);
			} else {
				echo json_encode([
					"status" => "failed",
					"message" => "No users found"
				]);
			}
		} else {
			if (count($users) > 0) {
				return $data;
			} else {
				echo json_encode([
					"status" => "failed",
					"message" => "No users found"
				]);
			}
		}
	} catch (PDOException $e) {
		echo json_encode([
			"status" => "failed",
			"message" => "Database error: " . $e->getMessage()
		]);
	}
}
