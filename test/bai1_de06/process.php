<?php
// Function to calculate the total price based on the drink type and number of cups
function tinhTien($loai, $soLy) {
    $donGia = 0;
    switch ($loai) {
        case "Cà phê":
            $donGia = 25000;
            break;
        case "Trà sữa":
            $donGia = 30000;
            break;
        case "Nước ép":
            $donGia = 35000;
            break;
        default:
            return "Loại đồ uống không hợp lệ";
    }

    $tongTien = $donGia * $soLy;
    if ($soLy >= 5) {
        $tongTien *= 0.9;
    }

    return number_format($tongTien, 0, ',', '.') . " đ";
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenKhach = $_POST['ten_khach'];
    $loai = $_POST['loai'];
    $soLy = $_POST['so_ly'];

    // Validate input
    if (empty($tenKhach)) {
        echo "<div class='alert'>Tên khách không được để trống.</div>";
    } elseif (!is_numeric($soLy) || $soLy < 1 || $soLy > 50) {
        echo "<div class='alert'>Số ly phải là số nguyên từ 1 đến 50.</div>";
    } else {
        $soLy = (int)$soLy;
        $tongTien = tinhTien($loai, $soLy);

        // Display the result
        echo "<div class='bill'>";
        echo "Khách: <b>" . htmlspecialchars($tenKhach) . "</b> | ";
        echo "Đồ uống: <b>" . htmlspecialchars($loai) . "</b> | ";
        echo "Số ly: <b>" . $soLy . "</b><br>";
        echo "Thành tiền: <b>" . $tongTien . "</b>";
        echo "</div>";
    }
}
?>