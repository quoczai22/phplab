<?php
require 'connect.php';

if (isset($_GET['id'])) {
    $stmt = $db->prepare("DELETE FROM students WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}

header("Location: list_student.php");
exit;
?>