<nav style="margin-bottom: 20px; font-size: 14px; color: #6b7280;">
    <a href="#" style="text-decoration: none; color: #3b82f6;">Trang chủ</a> 
    
    <?php if(request()->routeIs('articles.index')): ?>
        / <span>Danh sách bài viết</span>
    <?php endif; ?>

    <?php if(request()->routeIs('articles.create')): ?>
        / <a href="<?php echo e(route('articles.index')); ?>" style="text-decoration: none; color: #3b82f6;">Danh sách bài viết</a> 
        / <span>Tạo bài viết</span>
    <?php endif; ?>

    <?php if(request()->routeIs('articles.edit')): ?>
        / <a href="<?php echo e(route('articles.index')); ?>" style="text-decoration: none; color: #3b82f6;">Danh sách bài viết</a> 
        / <span>Chỉnh sửa bài viết</span>
    <?php endif; ?>

    <?php if(request()->routeIs('articles.show')): ?>
        / <a href="<?php echo e(route('articles.index')); ?>" style="text-decoration: none; color: #3b82f6;">Danh sách bài viết</a> 
        / <span>Chi tiết bài viết</span>
    <?php endif; ?>
</nav>
<?php /**PATH D:\1LuuDuLieuSV\2001240399_TrinhHuuKienQuoc\phplab\buoi7\Buoi7\resources\views/partials/breadcrumb.blade.php ENDPATH**/ ?>