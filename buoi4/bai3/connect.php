<?php 
$dbs = "mysql:host=localhost;dbname=labdb;charset=utf8"; 
$username = "root"; 
$password = ""; 

try { 
    $db = new PDO($dbs, $username, $password); 
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    // echo "Ket noi thanh cong"; 
} catch (PDOException $e) { 
    echo "Ket noi that bai: " . $e->getMessage(); 
} 
?>
