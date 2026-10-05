
<?php $__env->startSection('title', 'Danh sách bài viết'); ?>

<?php $__env->startSection('content'); ?>
    <h2>Danh sách bài viết</h2>

    <!-- Hiển thị Flash Message ngay trên bảng nếu có -->
    <?php if(session('success')): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #c3e6cb;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <table border="1" cellpadding="8" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($a['id']); ?></td>
                    <td><?php echo e($a['title']); ?></td>
                    <td>
                        <a href="<?php echo e(route('articles.show', $a['id'])); ?>">Xem</a> |
                        <a href="<?php echo e(route('articles.edit', $a['id'])); ?>">Sửa</a> |
                        
                        <!-- Form xoá an toàn bằng Confirm & Method Spoofing -->
                        <form action="<?php echo e(route('articles.destroy', $a['id'])); ?>" 
                              method="post" 
                              style="display:inline" 
                              onsubmit="return confirm('Bạn có chắc chắn muốn xoá bài viết này không?');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" style="color: red; cursor: pointer;">Xoá</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="3" style="text-align: center;">Chưa có bài viết.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php $__env->startPush('scripts'); ?>
        <script>
            // demo stack scripts
            console.log('Articles index loaded');
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\1LuuDuLieuSV\2001240399_TrinhHuuKienQuoc\phplab\buoi6\Lab01_Framework\resources\views/articles/index.blade.php ENDPATH**/ ?>