@extends('layouts.admin')

@section('title', 'Tạo Mã Giảm Giá Mới | Admin')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Tạo Mã Giảm Giá (Voucher) Mới</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Bảng Điều Khiển</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><a href="{{ route('admin.coupons') }}"><div class="text-tiny">Mã giảm giá</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Tạo mã</div></li>
    </ul>
</div>

<div class="wg-box">
    <form class="form-new-product form-style-1" method="POST" action="{{ route('admin.coupons.store') }}">
        @csrf
        <fieldset class="name">
            <div class="body-title">Mã Voucher <span class="tf-color-1">*</span></div>
            <input class="flex-grow" type="text" placeholder="Nhập mã (VD: VIP30, TET2026)" name="code" required>
        </fieldset>
        <fieldset class="category">
            <div class="body-title">Loại chiết khấu</div>
            <div class="select flex-grow">
                <select name="type">
                    <option value="percent">Theo phần trăm (%)</option>
                    <option value="fixed">Số tiền cố định (đ)</option>
                </select>
            </div>
        </fieldset>
        <fieldset class="name">
            <div class="body-title">Mức giảm <span class="tf-color-1">*</span></div>
            <input class="flex-grow" type="number" step="1" placeholder="Nhập số % hoặc số tiền giảm" name="value" required>
        </fieldset>
        <fieldset class="name">
            <div class="body-title">Giá trị đơn hàng tối thiểu (đ) <span class="tf-color-1">*</span></div>
            <input class="flex-grow" type="number" step="1" placeholder="Đơn tối thiểu áp dụng mã" name="cart_value" value="100000" required>
        </fieldset>
        <fieldset class="name">
            <div class="body-title">Ngày hết hạn <span class="tf-color-1">*</span></div>
            <input class="flex-grow" type="date" name="expiry_date" value="{{ now()->addMonths(3)->format('Y-m-d') }}" required>
        </fieldset>

        <div class="bot">
            <div></div>
            <button class="tf-button w208" type="submit">Lưu Mã Giảm Giá</button>
        </div>
    </form>
</div>
@endsection
