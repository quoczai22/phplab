<?php 
if (isset($_FILES['avatar'])) {
    $f = $_FILES['avatar'];

    if ($f['error'] === 0) {
        $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($ext, $allowed)) {
            echo "Chỉ cho phép định dạng ảnh (jpg, jpeg, png, gif, webp).";
            exit;
        }

        // Tự động tạo thư mục uploads nếu chưa tồn tại
        $dir = 'uploads/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $target = $dir . time() . "_" . basename($f['name']);

        if (move_uploaded_file($f['tmp_name'], $target)) {
            echo "Upload thành công!<br>";
            echo "<img src='$target' style='max-width:300px;'>";
        } else {
            echo "Không thể di chuyển file.";
        }
    } else {
        echo "Lỗi upload: " . $f['error'];
    }
} else {
    echo "Không có file được gửi.";
}
?>
