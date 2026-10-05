<?php $attributes = $attributes->exceptProps(['variant' => 'primary']); ?>
<?php foreach (array_filter((['variant' => 'primary']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
    // Định nghĩa class CSS tương ứng với từng biến thể
    $classes = $variant === 'danger' 
        ? 'btn btn-danger' 
        : 'btn btn-primary';
?>

<button <?php echo e($attributes->merge(['class' => $classes, 'type' => 'submit'])); ?>>
    <?php echo e($slot); ?>

</button>

<style>
    /* CSS cơ bản cho button */
    .btn {
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
    }
    .btn-primary {
        background-color: #007bff;
        color: white;
    }
    .btn-danger {
        background-color: #dc3545;
        color: white;
    }
</style>
<?php /**PATH D:\1LuuDuLieuSV\2001240399_TrinhHuuKienQuoc\phplab\buoi6\Lab01_Framework\resources\views/components/button.blade.php ENDPATH**/ ?>