@props(['variant' => 'primary'])

@php
    // Định nghĩa class CSS tương ứng với từng biến thể
    $classes = $variant === 'danger' 
        ? 'btn btn-danger' 
        : 'btn btn-primary';
@endphp

<button {{ $attributes->merge(['class' => $classes, 'type' => 'submit']) }}>
    {{ $slot }}
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
