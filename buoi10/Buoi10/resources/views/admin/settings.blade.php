@extends('layouts.admin')

@section('title', 'Cài Đặt Hệ Thống | Admin')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Cài Đặt Tài Khoản & Hệ Thống</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Bảng Điều Khiển</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Cài đặt</div></li>
    </ul>
</div>

<div class="wg-box">
    <div class="col-lg-12">
        <div class="page-content my-account__edit">
            <div class="my-account__edit-form">
                <form action="{{ route('admin.settings.update') }}" method="POST" class="form-new-product form-style-1">
                    @csrf
                    <fieldset class="name">
                        <div class="body-title">Họ và tên người quản trị <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" name="name" value="{{ $user->name ?? '' }}" required>
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">Số điện thoại liên hệ <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" name="mobile" value="{{ $user->phone ?? '' }}" required>
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">Địa chỉ Email quản trị <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="email" name="email" value="{{ $user->email ?? '' }}" required>
                    </fieldset>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="my-3">
                                <h5 class="text-uppercase mb-0">Đổi Mật Khẩu Quản Trị</h5>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="name">
                                <div class="body-title pb-3">Mật khẩu mới (tối thiểu 6 ký tự)</div>
                                <input class="flex-grow" type="password" placeholder="Để trống nếu không muốn đổi mật khẩu" name="new_password">
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <div class="my-3">
                                <button type="submit" class="btn btn-primary tf-button w208">Lưu Thay Đổi</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
