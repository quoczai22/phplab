@extends('layouts.app')

@section('title', 'Đăng Ký Tài Khoản | Fashion Store')

@section('content')
<div class="content">
    <div class="container"> 			         
        <div class="register" style="margin:40px 0;">
            <form action="{{ route('register.post') }}" method="POST"> 
                @csrf
                <div class="register-top-grid">
                    <h3 style="font-weight:bold;color:#111;">ĐĂNG KÝ THÀNH VIÊN MỚI</h3>
                    <p style="color:#666;margin-bottom:20px;">Vui lòng điền đầy đủ các thông tin bên dưới để tạo tài khoản mua sắm:</p>
                    <div class="mation">
                        <span>Họ và tên đệm <label>*</label></span>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Ví dụ: Nguyễn Văn" required> 
                    
                        <span>Tên <label>*</label></span>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Ví dụ: An" required> 
                     
                        <span>Địa chỉ Email <label>*</label></span>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com" required>

                        <span>Số điện thoại <label>*</label></span>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="0901234567" required>

                        <span>Mật khẩu (tối thiểu 6 ký tự) <label>*</label></span>
                        <input type="password" name="password" placeholder="Nhập mật khẩu" required style="border: 1px solid #EEE; outline-color:#FF5B36; width: 100%; font-size: 1em; padding: 0.5em; margin: 0.5em 0;">

                        <span>Xác nhận lại mật khẩu <label>*</label></span>
                        <input type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu" required style="border: 1px solid #EEE; outline-color:#FF5B36; width: 100%; font-size: 1em; padding: 0.5em; margin: 0.5em 0;">
                    </div>
                    <div class="clearfix"> </div>
                    <div style="margin-top:20px;">
                        <button type="submit" class="btn btn-danger btn-lg" style="background:#ff5722;border:none;padding:12px 35px;font-weight:bold;">
                            ĐĂNG KÝ NGAY
                        </button>
                        <a href="{{ route('login') }}" style="margin-left:20px;color:#444;font-size:14px;">Đã có tài khoản? <strong>Đăng nhập tại đây</strong></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
