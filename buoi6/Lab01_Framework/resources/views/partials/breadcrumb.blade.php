<nav style="margin-bottom: 20px; font-size: 14px; color: #6b7280;">
    <a href="#" style="text-decoration: none; color: #3b82f6;">Trang chủ</a> 
    
    @if(request()->routeIs('articles.index'))
        / <span>Danh sách bài viết</span>
    @endif

    @if(request()->routeIs('articles.create'))
        / <a href="{{ route('articles.index') }}" style="text-decoration: none; color: #3b82f6;">Danh sách bài viết</a> 
        / <span>Tạo bài viết</span>
    @endif

    @if(request()->routeIs('articles.edit'))
        / <a href="{{ route('articles.index') }}" style="text-decoration: none; color: #3b82f6;">Danh sách bài viết</a> 
        / <span>Chỉnh sửa bài viết</span>
    @endif

    @if(request()->routeIs('articles.show'))
        / <a href="{{ route('articles.index') }}" style="text-decoration: none; color: #3b82f6;">Danh sách bài viết</a> 
        / <span>Chi tiết bài viết</span>
    @endif
</nav>
