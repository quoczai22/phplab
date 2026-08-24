<?php
$city = mb_strtolower(trim($_GET['city'] ?? ''), 'UTF-8');
$cleanCity = str_replace([' ', '-', '_'], '', $city);

$data = [
    "hanoi" => ["temp" => 30, "desc" => "Nắng đẹp"],
    "hànội" => ["temp" => 30, "desc" => "Nắng đẹp"],
    "danang" => ["temp" => 32, "desc" => "Có mây"],
    "đànẵng" => ["temp" => 32, "desc" => "Có mây"],
    "saigon" => ["temp" => 34, "desc" => "Nắng nóng"],
    "tphcm" => ["temp" => 34, "desc" => "Nắng nóng"],
    "hồchíminh" => ["temp" => 34, "desc" => "Nắng nóng"],
    "hue" => ["temp" => 29, "desc" => "Mưa nhẹ"],
    "huế" => ["temp" => 29, "desc" => "Mưa nhẹ"]
];

header('Content-Type: application/json; charset=utf-8');
$result = $data[$cleanCity] ?? $data[$city] ?? ["temp" => 0, "desc" => "Không có dữ liệu"];
echo json_encode($result);
?>
