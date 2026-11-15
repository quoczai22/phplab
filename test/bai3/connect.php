<?php
// connect.php
try {
    $conn = new PDO("mysql:host=localhost;dbname=thuvien;charset=utf8mb4", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Kết nối thành công";
} catch(PDOException $e) {
    echo "Kết nối thất bại: " . $e->getMessage();
}
?>