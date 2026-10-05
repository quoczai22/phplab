
<?php $__env->startSection('content'); ?>
    <h2>Danh sách profile và User</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên User</th>
                <th>Address</th>
                <th>Phone</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $profiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($loop->iteration); ?></td>
                    <td><?php echo e($s->user->name ?? $s->user_id); ?></td>
                    <td><?php echo e($s->address); ?></td>
                    <td><?php echo e($s->phone); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\1LuuDuLieuSV\2001240399_TrinhHuuKienQuoc\phplab\buoi7\Buoi7\resources\views/profiles/index.blade.php ENDPATH**/ ?>