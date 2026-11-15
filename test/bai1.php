<?php
// Hàm tính tiền điện theo bậc thang
function tinhTienDien($soKwh) {
    $tien = 0;

    if ($soKwh <= 50) {
        $tien = $soKwh * 1800;
    } elseif ($soKwh <= 100) {
        $tien = (50 * 1800) + (($soKwh - 50) * 2000);
    } else {
        $tien = (50 * 1800) + (50 * 2000) + (($soKwh - 100) * 2500);
    }

    return $tien;
}

// Kiểm tra và xử lý form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenChuHo = $_POST['tenChuHo'] ?? '';
    $chiSoCu = $_POST['chiSoCu'] ?? '';
    $chiSoMoi = $_POST['chiSoMoi'] ?? '';

    $loi = '';

    // Kiểm tra dữ liệu
    if (empty($tenChuHo)) {
        $loi .= '<p style="color: red;">Tên chủ hộ không được để trống.</p>';
    }
    if (!is_numeric($chiSoCu) || $chiSoCu < 0 || !is_numeric($chiSoMoi) || $chiSoMoi < 0) {
        $loi .= '<p style="color: red;">Hai chỉ số phải là số nguyên không âm.</p>';
    }
    if ($chiSoMoi < $chiSoCu) {
        $loi .= '<p style="color: red;">Chỉ số mới phải lớn hơn hoặc bằng chỉ số cũ.</p>';
    }

    // Tính toán nếu không có lỗi
    if (empty($loi)) {
        $soKwh = $chiSoMoi - $chiSoCu;
        $tienDien = tinhTienDien($soKwh);
        $thueVAT = $tienDien * 0.08;
        $tongTien = $tienDien + $thueVAT;

        $tienDienFormatted = number_format($tienDien, 0, ',', '.');
        $thueVATFormatted = number_format($thueVAT, 0, ',', '.');
        $tongTienFormatted = number_format($tongTien, 0, ',', '.');

        $nhậnXét = '';
        switch ($soKwh) {
            case $soKwh < 100:
                $nhậnXét = 'Tiết kiệm';
                break;
            case $soKwh >= 100 && $soKwh <= 200:
                $nhậnXét = 'Bình thường';
                break;
            default:
                $nhậnXét = 'Sử dụng nhiều';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tính tiền điện</title>
</head>
<body>
    <h1>Tính tiền điện</h1>
    <form method="POST" action="">
        <label for="tenChuHo">Tên chủ hộ:</label>
        <input type="text" id="tenChuHo" name="tenChuHo" value="<?php echo htmlspecialchars($tenChuHo ?? ''); ?>">
        <br><br>

        <label for="chiSoCu">Chỉ số điện cũ:</label>
        <input type="text" id="chiSoCu" name="chiSoCu" value="<?php echo htmlspecialchars($chiSoCu ?? ''); ?>">
        <br><br>

        <label for="chiSoMoi">Chỉ số điện mới:</label>
        <input type="text" id="chiSoMoi" name="chiSoMoi" value="<?php echo htmlspecialchars($chiSoMoi ?? ''); ?>">
        <br><br>

        <button type="submit">Tính tiền điện</button>
    </form>

    <?php if (!empty($loi)) : ?>
        <div style="color: red;"><?php echo $loi; ?></div>
    <?php endif; ?>

    <?php if (!empty($tongTien)) : ?>
        <h2>Kết quả:</h2>
        <p>Tên chủ hộ: <?php echo htmlspecialchars($tenChuHo); ?></p>
        <p>Số kWh tiêu thụ: <?php echo htmlspecialchars($soKwh); ?></p>
        <p> tiền điện: <?php echo $tienDienFormatted; ?> đ</p>
        <p>Thế suất VAT 8%: <?php echo $thueVATFormatted; ?> đ</p>
        <p>Tổng tiền phải trả: <?php echo $tongTienFormatted; ?> đ</p>
        <p>Nhận xét: <?php echo htmlspecialchars($nhậnXét); ?></p>
    <?php endif; ?>
</body>
</html>