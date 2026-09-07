<?php
require 'connect.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    // Prepared Statement cho DELETE
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: list_students.php");
exit;
?>

