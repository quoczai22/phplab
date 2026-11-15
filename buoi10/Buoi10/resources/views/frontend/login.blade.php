@extends('layouts.app')

@section('title', 'Đăng Nhập Tài Khoản | Fashion Store')

@section('content')
<div class="content">
    <div class="container">
        <div class="row" style="margin:40px 0;">
            <div class="col-md-6">
                <div class="custom-card" style="padding:30px;">
                    <h3 style="margin-top:0;font-weight:bold;color:#111;">ĐĂNG NHẬP HỆ THỐNG</h3>
                    <p style="color:#666;margin-bottom:20px;">Vui lòng đăng nhập để tiếp tục mua sắm và theo dõi đơn hàng của bạn.</p>
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Địa chỉ Email *</label>
                            <input type="text" name="email" class="form-control" value="{{ old('email') }}" placeholder="nhapemail@example.com" required>
                        </div>
                        <div class="form-group">
                            <label>Mật khẩu *</label>
                            <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="remember" checked> Ghi nhớ đăng nhập</label>
                        </div>
                        <button type="submit" class="btn btn-danger btn-lg btn-block" style="background:#ff5722;border:none;font-weight:bold;padding:12px;">ĐĂNG NHẬP NGAY</button>
                    </form>
                    <div style="margin-top:20px;text-align:center;">
                        Chưa có tài khoản? <a href="{{ route('register') }}" style="font-weight:bold;color:#ff5722;">Đăng ký ngay</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="custom-card" style="background:#f8fafc;padding:30px;">
                    <h3 style="margin-top:0;font-weight:bold;color:#111;">QUYỀN LỢI THÀNH VIÊN</h3>
                    <p style="color:#666;">Trở thành thành viên của <strong>Fashion Store</strong> để nhận ngay các đặc quyền mua sắm:</p>
                    
                    <ul class="list-unstyled" style="line-height:2.4;margin:20px 0;font-size:15px;color:#333;">
                        <li>🎁 <strong>Tặng Voucher 10% - 20%</strong> cho đơn hàng đầu tiên.</li>
                        <li>⚡ <strong>Ưu tiên săn Flash Sale</strong> các bộ sưu tập thời trang giới hạn.</li>
                        <li>📦 <strong>Theo dõi hành trình đơn hàng</strong> trực tuyến 24/7 từ kho đến tận tay.</li>
                        <li>🔄 <strong>Đổi trả dễ dàng trong 7 ngày</strong> nếu không vừa size hoặc chưa hài lòng.</li>
                        <li>⭐ <strong>Tích điểm nâng hạng</strong> thành viên VIP với chiết khấu trọn đời.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
