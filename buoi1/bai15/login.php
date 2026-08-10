<?php 
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    if($user == 'admin' && $pass == '123456') {
        $_SESSION['user'] = $user; 
        header("Location: upload.php"); 
        exit;
    } else {
        $err = "Sai username hoặc password";
    }
}
?>
<form method="post">
    Username: <input name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
</form>
<?php if (!empty($err)) echo "<p style='color:red;'>$err</p>"; ?>
