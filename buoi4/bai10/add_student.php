<?php
require 'connect.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $birthday = !empty($_POST['birthday']) ? $_POST['birthday'] : null;

    if (!empty($name) && !empty($email)) {
        try {
            // Prepared Statement cho INSERT
            $stmt = $conn->prepare("INSERT INTO students (name, email, phone, birthday) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone, $birthday]);
            header("Location: list_students.php");
            exit;
        } catch (PDOException $e) {
            $message = "Lỗi khi thêm: " . $e->getMessage();
        }
    } else {
        $message = "Vui lòng nhập đầy đủ họ tên và email!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Thêm sinh viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Thêm sinh viên mới</h2>
    <?php if (!empty($message)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post" class="w-50">
        <div class="mb-3">
            <label class="form-label">Họ tên:</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Số điện thoại:</label>
            <input type="text" name="phone" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Ngày sinh:</label>
            <input type="date" name="birthday" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Thêm sinh viên</button>
        <a href="list_students.php" class="btn btn-secondary">Quay lại</a>
    </form>
</body>
</html>

