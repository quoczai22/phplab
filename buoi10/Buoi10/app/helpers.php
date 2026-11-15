<?php

if (!function_exists('vnd')) {
    /**
     * Định dạng số tiền theo kiểu Việt Nam, ví dụ 150000 -> "150.000 đ".
     */
    function vnd($amount): string
    {
        return number_format(round((float) $amount), 0, ',', '.') . ' đ';
    }
}
