@extends('layouts.app')

@section('title', 'Đặt Hàng & Thanh Toán | Fashion Store')

@section('content')
<div class="content">
    <div class="container" style="margin-top:25px;margin-bottom:40px;">
        <h3 style="font-weight:bold;margin-bottom:20px;">📦 THÔNG TIN ĐẶT HÀNG & THANH TOÁN</h3>

        <form action="{{ route('checkout.place') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-7">
                    <div class="custom-card">
                        <h4 style="margin-top:0;font-weight:bold;">1. Địa chỉ giao nhận hàng</h4>

                        @if($addresses->count() > 0)
                            <div class="alert alert-info" style="padding:10px;border-radius:6px;">
                                <strong>Sổ địa chỉ đã lưu:</strong> Bấm chọn địa chỉ nhận hàng nhanh:
                                <div style="margin-top:6px;">
                                    @foreach($addresses as $addr)
                                        <button type="button" class="btn btn-xs btn-default" style="margin-right:5px;margin-bottom:5px;"
                                            onclick="fillAddress('{{ addslashes($addr->name) }}', '{{ addslashes($addr->phone) }}', '{{ addslashes($addr->address) }}', '{{ addslashes($addr->city) }}')">
                                            📍 {{ $addr->name }} - {{ $addr->city }} {{ $addr->is_default ? '(Mặc định)' : '' }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="form-group">
                            <label>Họ và tên người nhận *</label>
                            <input type="text" id="f_name" name="name" class="form-control" value="{{ old('name', $defaultAddress->name ?? (auth()->user()->name ?? '')) }}" placeholder="Nhập họ và tên" required>
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại liên hệ *</label>
                            <input type="text" id="f_phone" name="phone" class="form-control" value="{{ old('phone', $defaultAddress->phone ?? (auth()->user()->phone ?? '')) }}" placeholder="Nhập số điện thoại" required>
                        </div>
                        <div class="form-group">
                            <label>Địa chỉ chi tiết (Số nhà, tên đường, Phường/Xã) *</label>
                            <input type="text" id="f_address" name="address" class="form-control" value="{{ old('address', $defaultAddress->address ?? '140 Lê Trọng Tấn, Tây Thạnh') }}" placeholder="Số nhà, tên đường..." required>
                        </div>
                        <div class="form-group">
                            <label>Tỉnh / Thành phố *</label>
                            <input type="text" id="f_city" name="city" class="form-control" value="{{ old('city', $defaultAddress->city ?? 'TP. Hồ Chí Minh') }}" placeholder="Tỉnh / Thành phố" required>
                        </div>
                        @auth
                        <div class="checkbox">
                            <label><input type="checkbox" name="save_address" value="1"> Lưu vào sổ địa chỉ cá nhân của tôi</label>
                        </div>
                        @endauth
                    </div>

                    <div class="custom-card">
                        <h4 style="margin-top:0;font-weight:bold;">2. Phương thức thanh toán</h4>
                        <div class="radio">
                            <label style="font-weight:bold;">
                                <input type="radio" name="payment_mode" value="cod" checked>
                                💵 Thanh toán khi nhận hàng (COD)
                            </label>
                        </div>
                        <hr style="margin:8px 0;">
                        <div class="radio">
                            <label style="font-weight:bold;color:#0284c7;">
                                <input type="radio" name="payment_mode" value="vnpay">
                                💳 Thanh toán trực tuyến qua cổng VNPay (Quét mã QR)
                            </label>
                        </div>
                        <hr style="margin:8px 0;">
                        <div class="radio">
                            <label style="font-weight:bold;color:#be185d;">
                                <input type="radio" name="payment_mode" value="momo">
                                📱 Thanh toán qua Ví điện tử MoMo
                            </label>
                        </div>
                        <hr style="margin:8px 0;">
                        <div class="radio">
                            <label style="font-weight:bold;color:#15803d;">
                                <input type="radio" name="payment_mode" value="banking">
                                🏦 Chuyển khoản ngân hàng 24/7 (VietQR tự động)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="custom-card">
                        <h4 style="margin-top:0;font-weight:bold;">3. Đơn hàng của bạn ({{ count($cart) }} món)</h4>
                        <table class="table table-condensed">
                            @foreach($cart as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item['name'] }}</strong> × {{ $item['quantity'] }}<br>
                                    <small class="text-muted">{{ $item['options'] }}</small>
                                </td>
                                <td class="text-right">{{ vnd($item['price'] * $item['quantity']) }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td>Tạm tính:</td>
                                <td class="text-right">{{ vnd($subtotal) }}</td>
                            </tr>
                            <tr>
                                <td>Khuyến mãi Voucher:</td>
                                <td class="text-right" style="color:#16a34a;">-{{ vnd($discount) }}</td>
                            </tr>
                            <tr>
                                <td>Thuế VAT (10%):</td>
                                <td class="text-right">{{ vnd($tax) }}</td>
                            </tr>
                            <tr style="font-size:18px;border-top:2px solid #111;">
                                <td><strong>Tổng cộng:</strong></td>
                                <td class="text-right" style="color:#e11d48;"><strong>{{ vnd($total) }}</strong></td>
                            </tr>
                        </table>

                        <button type="submit" class="btn btn-danger btn-lg btn-block" style="background:#ff5722;border:none;font-weight:bold;padding:12px;">
                            ✔ XÁC NHẬN ĐẶT HÀNG
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function fillAddress(name, phone, address, city) {
        document.getElementById('f_name').value = name;
        document.getElementById('f_phone').value = phone;
        document.getElementById('f_address').value = address;
        document.getElementById('f_city').value = city;
    }
</script>
@endsection
