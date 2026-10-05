<div>
    <!-- Very little is needed to make a happy life. - Marcus Aurelius -->
    <?php $attributes = $attributes->exceptProps(['type' => 'success', 'title' => 'Thông báo']); ?>
<?php foreach (array_filter((['type' => 'success', 'title' => 'Thông báo']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>
    <div style="padding:10px;border-radius:8px;margin-bottom:10px; ackground: <?php echo e($type === 'success' ? '#ECFDF5' : '#FEF3C7'); ?>; color: <?php echo e($type === 'success' ? '#065F46' : '#92400E'); ?>;">
        <strong><?php echo e($title); ?>:</strong> <?php echo e($slot); ?>

    </div>
</div><?php /**PATH D:\1LuuDuLieuSV\2001240399_TrinhHuuKienQuoc\phplab\buoi6\Lab01_Framework\resources\views/components/alert.blade.php ENDPATH**/ ?>