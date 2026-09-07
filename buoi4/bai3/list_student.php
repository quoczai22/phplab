<?php 



require 'connect.php'; 
$stmt = $db->prepare("SELECT * FROM students"); 
$stmt->execute(); 
$students = $stmt->fetchAll(PDO::FETCH_ASSOC); 
?> 

<table border="1" cellpadding="5" cellspacing="0"> 
    <tr> 
        <th>ID</th> 
        <th>Họ tên</th> 
        <th>Email</th> 
        <th>SĐT</th> 
        <th>Thao tác</th>
    </tr> 
    <?php foreach ($students as $row): ?> 
    <tr> 
        <td><?= $row['id'] ?> </td> 
        <td><?= $row['name'] ?></td> 
        <td><?= $row['email'] ?></td> 
        <td><?= $row['phone'] ?></td> 
        <td>
            <a href="edit_student.php?id=<?= $row['id'] ?>">Sửa</a> | 
            <a href="delete_student.php?id=<?= $row['id'] ?>" onclick="return confirm('Xóa?')">Xóa</a>
        </td>
    </tr> 
    <?php endforeach; ?> 
</table>
