@extends('layouts.app')

@section('title', 'Tra Cứu Đơn Hàng | Fashion Store')

@section('content')
<div class="content">
    <div class="container" style="margin-top:25px;margin-bottom:40px;">
        <div class="custom-card">
            <h3 style="margin-top:0;font-weight:bold;color:#111;">🚚 TRA CỨU HÀNH TRÌNH ĐƠN HÀNG</h3>
            <p style="color:#666;">Nhập mã đơn hàng của bạn (được gửi trong email hoặc tin nhắn xác nhận khi đặt hàng thành công) để theo dõi tiến độ giao nhận:</p>
            <form action="{{ route('order.track') }}" method="GET" class="form-inline" style="margin-bottom:15px;">
                <input type="text" name="order_code" class="form-control input-lg" style="width:340px;" placeholder="Ví dụ: ORD-2026002" value="{{ request('order_code', 'ORD-2026002') }}" required>
                <button type="submit" class="btn btn-danger btn-lg" style="background:#ff5722;border:none;font-weight:bold;">🔍 Tra Cứu Ngay</button>
            </form>

            <div>
                <small class="text-muted">Gợi ý: Quý khách cũng có thể tra cứu toàn bộ đơn hàng trong mục <a href="{{ route('account.profile') }}" style="color:#ff5722;font-weight:600;">Tài khoản của tôi</a>.</small>
            </div>
        </div>

        @if($order)
            @include('frontend.partials.order-timeline', ['order' => $order])
        @elseif(request()->filled('order_code'))
            <div class="alert alert-warning" style="padding:15px;border-radius:6px;">Không tìm thấy đơn hàng nào với mã <strong>{{ request('order_code') }}</strong>. Vui lòng kiểm tra lại.</div>
        @endif
    </div>
</div>
@endsection
