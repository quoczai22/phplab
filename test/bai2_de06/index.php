<?php
// Tạo mảng đối tượng Drink
require_once 'Drink.php';
$drinks = [
    new Drink("DU01", "Cà phê sữa đá", 25000, 120),
    new Drink("DU02", "Trà sữa", 30000, 80),
    new Drink("DU03", "Nước ép", 35000, 150),
    new Drink("DU04", "Cà phê đen", 20000, 90)
];

// Tính tổng số ly đã bán và tổng doanh thu
$totalLyDaBan = 0;
$totalDoanhThu = 0;
foreach ($drinks as $drink) {
    $totalLyDaBan += $drink->getSoLyDaBan();
    $totalDoanhThu += $drink->doanhThu();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Câu 2 - Thống kê đồ uống</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="topbar">
        <h1>QUÁN CÀ PHÊ HẠT NÂU</h1>
        <p>Thống kê bán hàng trong tháng</p>
    </div>
    <div class="wrap">
        <h2>Thống kê đồ uống (OOP)</h2>
        <table>
            <tr>
                <th>STT</th>
                <th>Mã</th>
                <th>Tên đồ uống</th>
                <th>Giá bán</th>
                <th>Số ly đã bán</th>
                <th>Xếp hạng</th>
                <th>Doanh thu</th>
            </tr>
            <?php foreach ($drinks as $index => $drink): ?>
            <tr>
                <td><?php echo $index + 1; ?></td>
                <td><?php echo $drink->getMaDoUong(); ?></td>
                <td><?php echo $drink->getTenDoUong(); ?></td>
                <td class="price"><?php echo number_format($drink->getGiaBan(), 0, ',', '.'); ?> đ</td>
                <td><?php echo $drink->getSoLyDaBan(); ?></td>
                <td><span class="<?php echo ($drink->xepHang() == "Bán chạy") ? "tag-hot" : "tag-normal"; ?>"><?php echo $drink->xepHang(); ?></span></td>
                <td class="price"><?php echo number_format($drink->doanhThu(), 0, ',', '.'); ?> đ</td>
            </tr>
            <?php endforeach; ?>
            <tr class="sum">
                <td colspan="4">Tổng cộng</td>
                <td><?php echo $totalLyDaBan; ?></td>
                <td></td>
                <td class="price"><?php echo number_format($totalDoanhThu, 0, ',', '.'); ?> đ</td>
            </tr>
        </table>
    </div>
</body>
</html>