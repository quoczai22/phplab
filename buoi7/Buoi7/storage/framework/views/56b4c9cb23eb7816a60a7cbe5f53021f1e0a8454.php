
<?php $__env->startSection('content'); ?>
<h2>Danh sách sản phẩm</h2>
<table>
    <tr>
        <th>Tên</th>
        <th>Giá</th>
        <th>Danh mục</th>
    </tr>
    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e($p->name); ?></td>
        <td><?php echo e(number_format($p->price)); ?> đ</td>
        <td><?php echo e($p->category->name); ?></td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>
<?php echo e($products->links()); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\1LuuDuLieuSV\2001240399_TrinhHuuKienQuoc\phplab\buoi7\Buoi7\resources\views/products/index.blade.php ENDPATH**/ ?>