<?php
require 'connect.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = '';

if ($id <= 0) {
    header("Location: list_students.php");
    exit;
}

// Xử lý cập nhật bằng Prepared Statement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $birthday = !empty($_POST['birthday']) ? $_POST['birthday'] : null;

    try {
        $stmt = $conn->prepare("UPDATE students SET name = ?, email = ?, phone = ?, birthday = ? WHERE id = ?");
        $stmt->execute([$name, $email, $phone, $birthday, $id]);
        header("Location: list_students.php");
        exit;
    } catch (PDOException $e) {
        $message = "Lỗi khi cập nhật: " . $e->getMessage();
    }
}

// Lấy dữ liệu sinh viên bằng Prepared Statement
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    echo "Không tìm thấy sinh viên!";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sửa sinh viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Cập nhật thông tin sinh viên</h2>
    <?php if (!empty($message)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post" class="w-50">
        <div class="mb-3">
            <label class="form-label">Họ tên:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($student['name'] ?? '') ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($student['email'] ?? '') ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Số điện thoại:</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($student['phone'] ?? '') ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Ngày sinh:</label>
            <input type="date" name="birthday" value="<?= htmlspecialchars($student['birthday'] ?? '') ?>" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="list_students.php" class="btn btn-secondary">Quay lại</a>
    </form>
</body>
</html>

