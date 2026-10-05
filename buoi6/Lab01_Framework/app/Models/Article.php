<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public function resolveRouteBinding($value, $field = null)
    {
        // 1. Tạo danh sách các bài viết giả lập
        $mockArticles = [
        1 => ['id' => 1, 'title' => 'Giới thiệu Laravel 12', 'body' => 'Nội dung A'],
        2 => ['id' => 2, 'title' => 'Blade Components', 'body' => 'Nội dung B'],
    ];

        // 2. Kiểm tra xem ID truyền vào route có tồn tại không
        if (array_key_exists($value, $mockArticles)) {
            // Nếu không tìm thấy, ném ra lỗi 404 giống như findOrFail
            $article=new self();
            $article->forceFill($mockArticles[$value]);
            $article->exists = true;

            return $article;
        }

        abort(404,'Khong tim thay bai viet');
    }
}
