<?php
require_once 'Book.php';

// Tạo mảng đối tượng Book
$books = [
    new Book("001", "Sách A", "Tác giả A", 2020, 50000, 10),
    new Book("002", "Sách B", "Tác giả B", 2023, 60000, 5),
    new Book("003", "Sách C", "Tác giả C", 2021, 55000, 8),
    new Book("004", "Sách D", "Tác giả D", 2022, 52000, 12),
    new Book("005", "Sách E", "Tác giả E", 2020, 58000, 7)
];

// Tính tổng số lượng và tổng thành tiền
$totalQuantity = 0;
$totalPrice = 0;
$maxPrice = 0;
$mostNewBooks = 0;

foreach ($books as $index => $book) {
    $totalQuantity += $book->getSoLuong();
    $totalPrice += $book->thanhTien();

    if ($book->thanhTien() > $maxPrice) {
        $maxPrice = $book->thanhTien();
        $maxPriceBook = $book;
    }

    if ($book->loaiSach() === "Sách mới") {
        $mostNewBooks++;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sách</title>
    <style>
        .new-book {
            background-color: #f9f9e6;
        }
    </style>
</head>
<body>
    <h1>Danh sách sách</h1>
    <table border="1">
        <tr>
            <th>STT</th>
            <th>Mã sách</th>
            <th>Tên sách</th>
            <th>Tác giả</th>
            <th>Năm XB</th>
            <th>Đơn giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
            <th>Loại</th>
        </tr>
        <?php foreach ($books as $index => $book) : ?>
            <tr>
                <td><?php echo $index + 1; ?></td>
                <td><?php echo $book->getMaSach(); ?></td>
                <td><?php echo $book->getTenSach(); ?></td>
                <td><?php echo $book->getTacGia(); ?></td>
                <td><?php echo $book->getNamXB(); ?></td>
                <td><?php echo number_format($book->getDonGia(), 0, ',', '.'); ?></td>
                <td><?php echo number_format($book->getSoLuong(), 0, ',', '.'); ?></td>
                <td><?php echo number_format($book->thanhTien(), 0, ',', '.'); ?></td>
                <td class="<?php echo $book->loaiSach() === "Sách mới" ? 'new-book' : ''; ?>"><?php echo $book->loaiSach(); ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="7">Tổng số lượng: <?php echo number_format($totalQuantity, 0, ',', '.'); ?></td>
            <td colspan="2">Tổng thành tiền: <?php echo number_format($totalPrice, 0, ',', '.'); ?></td>
        </tr>
    </table>

    <h2>Thông tin sách có thành tiền cao nhất</h2>
    <ul>
        <li>Mã sách: <?php echo $maxPriceBook->getMaSach(); ?></li>
        <li>Tên sách: <?php echo $maxPriceBook->getTenSach(); ?></li>
        <li>Thành tiền: <?php echo number_format($maxPriceBook->thanhTien(), 0, ',', '.'); ?></li>
    </ul>

    <h2>Số quyển "Sách mới"</h2>
    <p><?php echo $mostNewBooks; ?></p>
</body>
</html>