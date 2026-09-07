<?php
require 'connect.php';

// Lấy từ khóa tìm kiếm
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

// Truy vấn tìm kiếm bằng Prepared Statement (không cần LIMIT và OFFSET)
$sql = "SELECT * FROM students WHERE name LIKE :keyword ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([':keyword' => "%$keyword%"]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bài 10: Tìm kiếm sinh viên (Prepared Statement)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Bài tập 10: Tìm kiếm sinh viên</h2>

    <!-- Form tìm kiếm -->
    <form method="get" class="row mb-3">
        <div class="col-md-4">
            <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" class="form-control" placeholder="Nhập tên cần tìm">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            <?php if (!empty($keyword)): ?>
                <a href="timkiem.php" class="btn btn-secondary">Đặt lại</a>
            <?php endif; ?>
        </div>
    </form>

    <!-- Bảng hiển thị kết quả -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Họ và tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Ngày sinh</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($students): ?>
                <?php foreach ($students as $index => $row): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['phone'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['birthday'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Không tìm thấy sinh viên nào</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>