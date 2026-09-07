<?php
require 'connect.php';

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $db->prepare("UPDATE students SET name = ?, email = ?, phone = ? WHERE id = ?");
    $stmt->execute([$_POST['name'], $_POST['email'], $_POST['phone'], $id]);
    header("Location: list_student.php");
    exit;
}

$stmt = $db->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<form method="post">
    <label>Họ tên:</label> <input type="text" name="name" value="<?= $student['name'] ?>" required><br>
    <label>Email:</label> <input type="email" name="email" value="<?= $student['email'] ?>" required><br>
    <label>SĐT:</label> <input type="text" name="phone" value="<?= $student['phone'] ?>"><br>
    <button type="submit">Cập nhật</button>
</form>

