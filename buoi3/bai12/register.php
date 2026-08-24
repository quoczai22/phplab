<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbname = 'lab_buoi3';

// Thử kết nối port 33066 hoặc 3306
$conn = @mysqli_connect($host, $user, $pass, '', 33066);
if (!$conn) {
    $conn = @mysqli_connect($host, $user, $pass, '', 3306);
}

if (!$conn) {
    die("Lỗi kết nối MySQL: " . mysqli_connect_error());
}

// Tạo CSDL và bảng nếu chưa có
mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $dbname");
mysqli_select_db($conn, $dbname);
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100),
    username VARCHAR(50),
    email VARCHAR(100),
    phone VARCHAR(20),
    password VARCHAR(255)
)");

$fullname = $_POST['fullname'] ?? '';
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($fullname) || empty($username) || empty($email) || empty($phone) || empty($password)) {
    echo "Vui lòng nhập đầy đủ thông tin!";
    exit;
}

$sql = "INSERT INTO users (fullname, username, email, phone, password) VALUES ('$fullname', '$username', '$email', '$phone', '$password')";
if (mysqli_query($conn, $sql)) {
    echo "Đăng ký thành công!";
} else {
    echo "Lỗi: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
