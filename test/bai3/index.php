<?php
// cau3/index.php
require_once 'connect.php';

// Lấy dữ liệu từ cơ sở dữ liệu
$sql = "SELECT s.ma_sach, s.ten_sach, s.tac_gia, s.nam_xb, s.don_gia, t.ten_tl 
        FROM sach s 
        JOIN theloai t ON s.ma_tl = t.ma_tl 
        ORDER BY s.don_gia DESC";
$stmt = $conn->query($sql);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalBooks = count($results);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sách</title>
</head>
<body>
    <button><a href="add.php">Thêm sách</a></button>
    <h1>Danh sách sách</h1>
    <p>Tổng số sách: <?php echo $totalBooks; ?></p>
    <table border="1">
        <tr>
            <th>Mã sách</th>
            <th>Tên sách</th>
            <th>Tác giả</th>
            <th>Năm XB</th>
            <th>Đơn giá</th>
            <th>Tên thể loại</th>
        </tr>
        <?php foreach ($results as $row) : ?>
            <tr>
                <td><?php echo $row['ma_sach']; ?></td>
                <td><?php echo $row['ten_sach']; ?></td>
                <td><?php echo $row['tac_gia']; ?></td>
                <td><?php echo $row['nam_xb']; ?></td>
                <td><?php echo number_format($row['don_gia'], 0, ',', '.'); ?></td>
                <td><?php echo $row['ten_tl']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>