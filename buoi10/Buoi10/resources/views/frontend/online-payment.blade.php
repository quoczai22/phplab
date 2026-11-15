@extends('layouts.app')

@section('title', 'Cổng Thanh Toán Trực Tuyến | Fashion Store')

@section('content')
<div class="content">
    <div class="container" style="margin-top:30px;margin-bottom:50px;">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="custom-card text-center" style="border:2px solid #0284c7;padding:30px;">
                    <span class="label label-info" style="font-size:13px;padding:5px 12px;">CỔNG THANH TOÁN TRỰC TUYẾN AN TOÀN</span>
                    <h3 style="font-weight:800;margin-top:15px;color:#0f172a;">
                        THANH TOÁN QUA {{ strtoupper($order->payment_mode) }}
                    </h3>
                    <p style="color:#555;font-size:15px;">Mã đơn hàng: <strong>{{ $order->order_code }}</strong> | Số tiền thanh toán: <strong style="color:#e11d48;font-size:22px;">{{ vnd($order->total) }}</strong></p>

                    <!-- Khung quét mã QR thanh toán -->
                    <div style="background:#f8fafc;border:1px dashed #94a3b8;padding:20px;border-radius:8px;margin:20px auto;max-width:320px;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode('FASHIONSTORE|' . $order->order_code . '|' . $order->total) }}"
                             alt="QR Payment" style="width:190px;height:190px;margin:0 auto;display:block;">
                        <div style="margin-top:12px;font-size:13px;color:#334155;">
                            Nội dung chuyển khoản: <strong>THANHTOAN {{ $order->order_code }}</strong>
                        </div>
                    </div>

                    <form action="{{ route('checkout.payment.confirm', $order->order_code) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg btn-block" style="font-weight:bold;padding:12px;">
                            ✔ XÁC NHẬN ĐÃ HOÀN TẤT THANH TOÁN
                        </button>
                    </form>
                    <div style="margin-top:15px;">
                        <a href="{{ route('account.order.detail', $order->order_code) }}" class="text-muted" style="font-size:13px;">Thanh toán sau / Xem chi tiết đơn hàng</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
