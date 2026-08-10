<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['n'])) {
    $n = (int)$_POST['n'];
    $sum = 0;
    for ($i = 1; $i <= $n; $i++) {
        $sum += $i;
    }
    $res = "Tổng các số từ 1 đến $n là: <b>$sum</b>";
}
?>
<h3>Bài 16: Tính tổng các số từ 1 đến N</h3>
<form method="post">
    Nhập số N: <input type="number" name="n" min="1" required>
    <input type="submit" value="Tính tổng">
</form>
<?php if (!empty($res)) echo "<p>$res</p>"; ?>
