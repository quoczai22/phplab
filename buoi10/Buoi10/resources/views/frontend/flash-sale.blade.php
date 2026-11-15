@extends('layouts.app')

@section('title', 'Flash Sale & Kho Voucher Giảm Giá | Fashion Store')

@section('content')
<div class="content">
    <div class="container">
        <!-- Flash Sale Banner -->
        <div style="background:linear-gradient(135deg, #be123c, #fb7185);color:#fff;padding:30px;border-radius:10px;margin:20px 0;">
            <div class="row">
                <div class="col-md-8">
                    <h2 style="margin-top:0;font-weight:800;">⚡ SIÊU SALE GIỜ VÀNG - FLASH SALE 2026</h2>
                    <p style="font-size:16px;">Săn ngay sản phẩm giảm giá tới 40% + Lưu mã Voucher giảm thêm tại giỏ hàng!</p>
                </div>
                <div class="col-md-4 text-right">
                    <div style="background:rgba(0,0,0,0.25);padding:15px;border-radius:8px;display:inline-block;text-align:center;">
                        <div style="font-size:12px;text-transform:uppercase;">Kết thúc sau</div>
                        <div id="flash-sale-page-timer" style="font-size:28px;font-weight:bold;letter-spacing:2px;">04 : 58 : 19</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vouchers Section (Group B) -->
        <div class="custom-card">
            <h3 style="margin-top:0;color:#d97706;font-weight:bold;">🎟️ KHO MÃ GIẢM GIÁ (VOUCHER) ĐANG CÓ HIỆU LỰC</h3>
            <div class="row">
                @foreach($coupons as $cp)
                <div class="col-md-4">
                    <div style="border:2px dashed #f59e0b;background:#fffbeb;padding:15px;border-radius:8px;margin-bottom:15px;">
                        <div class="clearfix">
                            <span class="label label-danger pull-right" style="font-size:13px;">
                                Giảm {{ $cp->type == 'percent' ? $cp->value . '%' : vnd($cp->value) }}
                            </span>
                            <h4 style="margin:0;color:#b45309;font-weight:800;">MÃ: {{ $cp->code }}</h4>
                        </div>
                        <p style="margin:8px 0 4px;font-size:13px;color:#444;">
                            Áp dụng cho đơn hàng từ <strong>{{ vnd($cp->cart_value) }}</strong>
                        </p>
                        <small style="color:#777;">Hạn sử dụng: {{ $cp->expiry_date }}</small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Flash Sale Products Grid -->
        <div class="custom-card">
            <h3 style="margin-top:0;color:#e11d48;font-weight:bold;">🔥 SẢN PHẨM ĐANG FLASH SALE</h3>
            <div class="row">
                @foreach($flashProducts as $fp)
                <div class="col-md-3" style="margin-bottom:25px;">
                    <div style="border:1px solid #fecdd3;border-radius:8px;padding:12px;position:relative;">
                        <span class="flash-badge">-{{ $fp->discountPercent() }}%</span>
                        <a href="{{ route('product.single', $fp->slug) }}">
                            <img src="{{ asset($fp->image) }}" class="img-responsive" style="height:230px;width:100%;object-fit:cover;border-radius:4px;">
                        </a>
                        <h4 style="font-size:15px;margin:12px 0 6px;height:36px;overflow:hidden;">
                            <a href="{{ route('product.single', $fp->slug) }}" style="color:#111;">{{ $fp->name }}</a>
                        </h4>
                        <div style="margin-bottom:10px;">
                            <span style="color:#e11d48;font-size:18px;font-weight:bold;">{{ vnd($fp->effectivePrice()) }}</span>
                            <del style="color:#999;font-size:13px;margin-left:6px;">{{ vnd($fp->regular_price) }}</del>
                        </div>
                        <div class="progress" style="height:14px;margin-bottom:10px;background:#ffe4e6;">
                            <div class="progress-bar progress-bar-danger" style="width: 75%;font-size:10px;line-height:14px;">Đã bán 75%</div>
                        </div>
                        <a href="{{ route('cart.quick.add', $fp->id) }}" class="btn btn-danger btn-block">⚡ Mua Ngay Giá Sốc</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        var duration = 4 * 3600 + 58 * 60 + 19; // 4 giờ 58 phút 19 giây
        var now = Math.floor(Date.now() / 1000);
        var targetTime = localStorage.getItem('flash_sale_end_time');

        if (!targetTime || parseInt(targetTime) <= now) {
            targetTime = now + duration;
            localStorage.setItem('flash_sale_end_time', targetTime);
        } else {
            targetTime = parseInt(targetTime);
        }

        function updateCountdown() {
            var current = Math.floor(Date.now() / 1000);
            var remain = Math.max(0, targetTime - current);

            var h = Math.floor(remain / 3600);
            var m = Math.floor((remain % 3600) / 60);
            var s = remain % 60;

            var hStr = (h < 10 ? '0' + h : h);
            var mStr = (m < 10 ? '0' + m : m);
            var sStr = (s < 10 ? '0' + s : s);

            var el = document.getElementById('flash-sale-page-timer');
            if (el) {
                el.innerText = hStr + ' : ' + mStr + ' : ' + sStr;
            }
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    })();
</script>
@endsection

