@extends('layouts.app')

@section('title', 'Liên Hệ Chăm Sóc Khách Hàng | Fashion Store')

@section('content')
<div class="content">
    <div class="container">
        <div class="contact">
            <div class="contact-in">
                <h3 style="font-weight:bold;color:#111;">LIÊN HỆ VỚI CHÚNG TÔI</h3>
                <div class="col-md-9 contact-left">
                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <div>
                            <span>Họ và tên của bạn *</span>
                            <input name="userName" type="text" class="textbox" placeholder="Nhập họ và tên..." required>
                        </div>
                        <div>
                            <span>Địa chỉ Email *</span>
                            <input name="userEmail" type="email" class="textbox" placeholder="email@example.com" required>
                        </div>
                        <div>
                            <span>Nội dung tin nhắn / Góp ý *</span>
                            <textarea name="userMsg" placeholder="Nội dung cần hỗ trợ hoặc phản ánh về đơn hàng..." required></textarea>
                        </div>
                        <div>
                            <span><input type="submit" value="Gửi Tin Nhắn"></span>
                        </div>
                    </form>
                </div>

                <div class="col-md-3 contact-right">
                    <h5>Thông Tin Doanh Nghiệp</h5>
                    <p>140 Lê Trọng Tấn, Phường Tây Thạnh,</p>
                    <p>Quận Tân Phú, TP. Hồ Chí Minh</p>
                    <p>Việt Nam</p>
                    <p>Hotline: 0901 234 567</p>
                    <p>Email: <a href="mailto:support@fashionstore.com">support@fashionstore.com</a></p>
                    <p>Giờ làm việc: 08:00 - 21:30 (Thứ 2 - CN)</p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
@endsection
