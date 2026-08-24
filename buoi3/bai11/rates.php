<?php
$rates = [
    ["code" => "USD", "name" => "Đô la Mỹ", "buy" => 25120, "sell" => 25490],
    ["code" => "EUR", "name" => "Đồng Euro", "buy" => 26850, "sell" => 28320],
    ["code" => "GBP", "name" => "Bảng Anh", "buy" => 31450, "sell" => 32790],
    ["code" => "JPY", "name" => "Yên Nhật", "buy" => 160.5, "sell" => 169.8],
    ["code" => "AUD", "name" => "Đô la Úc", "buy" => 16420, "sell" => 17120]
];

header('Content-Type: application/json; charset=utf-8');
echo json_encode($rates);
?>
