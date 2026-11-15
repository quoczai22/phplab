<?php 
// add.php
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ma_sach = $_POST['ma_sach'];
    $ten_sach = $_POST['ten_sach'];
    $tac_gia = $_POST['tac_gia'];
    $nam_xb = $_POST['nam_xb'];
    $don_gia = $_POST['don_gia'];
    $ma_tl = $_POST['ma_tl'];

    try {
        $sql = "INSERT INTO sach (ma_sach, ten_sach, tac_gia, nam_xb, don_gia, ma_tl) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$ma_sach, $ten_sach, $tac_gia, $nam_xb, $don_gia, $ma_tl]);
        echo "Thêm sách thành công";
    } catch(PDOException $e) {
        echo "Lỗi khi thêm sách: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sách</title>
</head>
<body>
    <h1>Thêm sách</h1>
    <form method="POST" action="">
        <label for="ma_sach">Mã sách:</label>
        <input type="text" id="ma_sach" name="ma_sach" required>
        <br><br>

        <label for="ten_sach">Tên sách:</label>
        <input type="text" id="ten_sach" name="ten_sach" required>
        <br><br>

        <label for="tac_gia">Tác giả:</label>
        <input type="text" id="tac_gia" name="tac_gia" required>
        <br><br>

        <label for="nam_xb">Năm xuất bản:</label>
        <input type="number" id="nam_xb" name="nam_xb" required>
        <br><br>

        <label for="don_gia">Đơn giá:</label>
        <input type="number" id="don_gia" name="don_gia" required>
        <br><br>

        <label for="ma_tl">Mã thể loại:</label>
        <input type="text" id="ma_tl" name="ma_tl" required>
        <br><br>

        <button type="submit">Thêm sách</button>
    </form>
</body>
</html>

