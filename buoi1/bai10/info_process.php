<?php 
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $hoten = htmlspecialchars($_POST['hoten'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $sdt = htmlspecialchars($_POST['sdt'] ?? '');
    echo "Ho va Ten : $hoten <br> Email : $email <br> Phone : $sdt";
}
else{
    echo "Phai submit bang phuong thuc POST";
}
?>