@extends('layouts.app')

@section('title', 'Giỏ Hàng Của Bạn | Fashion Store')

@section('content')
<div class="content">
    <div class="container" style="margin-top:25px;margin-bottom:40px;">
        <h3 style="font-weight:bold;margin-bottom:20px;">🛒 GIỎ HÀNG CỦA BẠN</h3>

        @if(empty($cart))
            <div class="custom-card text-center" style="padding:50px;">
                <h4>Giỏ hàng của bạn đang trống!</h4>
                <p>Hãy khám phá các sản phẩm thời trang mới nhất và săn ưu đãi Flash Sale ngay hôm nay.</p>
                <a href="{{ route('collection') }}" class="btn btn-danger btn-lg" style="background:#ff5722;border:none;margin-top:10px;">TIẾP TỤC MUA SẮM</a>
            </div>
        @else
            <div class="row">
                <div class="col-md-8">
                    <div class="custom-card">
                        <form action="{{ route('cart.update') }}" method="POST">
                            @csrf
                            <table class="table table-bordered table-hover" style="vertical-align:middle;">
                                <thead style="background:#f8fafc;">
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Phân loại</th>
                                        <th class="text-center">Đơn giá</th>
                                        <th class="text-center" style="width:110px;">Số lượng</th>
                                        <th class="text-center">Thành tiền</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $rowId => $item)
                                    <tr>
                                        <td>
                                            <img src="{{ asset($item['image']) }}" style="width:55px;height:65px;object-fit:cover;float:left;margin-right:10px;border-radius:4px;">
                                            <a href="{{ route('product.single', $item['slug']) }}" style="font-weight:bold;color:#111;">{{ $item['name'] }}</a>
                                        </td>
                                        <td><span class="label label-default">{{ $item['options'] }}</span></td>
                                        <td class="text-center">{{ vnd($item['price']) }}</td>
                                        <td class="text-center">
                                            <input type="number" name="quantities[{{ $rowId }}]" value="{{ $item['quantity'] }}" min="1" max="50" class="form-control text-center">
                                        </td>
                                        <td class="text-center" style="font-weight:bold;color:#e11d48;">
                                            {{ vnd($item['price'] * $item['quantity']) }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('cart.remove', $rowId) }}" class="btn btn-xs btn-danger" title="Xóa">✖</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="clearfix">
                                <a href="{{ route('collection') }}" class="btn btn-default">← Tiếp tục mua sắm</a>
                                <button type="submit" class="btn btn-primary pull-right">↻ Cập nhật giỏ hàng</button>
                            </div>
                        </form>
                    </div>

                    <!-- MÃ GIẢM GIÁ / VOUCHER -->
                    <div class="custom-card" style="background:#fffbeb;border-color:#fde68a;">
                        <h4 style="margin-top:0;color:#b45309;font-weight:bold;">🎟️ MÃ GIẢM GIÁ & ƯU ĐÃI</h4>
                        <form action="{{ route('cart.coupon.apply') }}" method="POST" class="form-inline" style="margin-bottom:12px;">
                            @csrf
                            <input type="text" name="code" class="form-control" placeholder="Nhập mã ưu đãi (VD: OFF10, SUMMER20)" value="{{ $coupon['code'] ?? '' }}" style="width:280px;" required>
                            <button type="submit" class="btn btn-warning" style="font-weight:bold;">Áp Dụng</button>
                            @if($coupon)
                                <a href="{{ route('cart.coupon.remove') }}" class="btn btn-danger btn-sm" style="margin-left:8px;">Gỡ mã ({{ $coupon['code'] }})</a>
                            @endif
                        </form>
                        <div>
                            <small style="color:#666;">Mã ưu đãi có thể sử dụng (Bấm để chọn): </small>
                            @foreach($availableCoupons as $cp)
                                <span class="label label-warning" style="cursor:pointer;margin-right:6px;font-size:12px;" onclick="document.querySelector('input[name=code]').value='{{ $cp->code }}'">
                                    {{ $cp->code }} (Giảm {{ $cp->type == 'percent' ? $cp->value.'%' : '$'.$cp->value }} - Đơn từ {{ vnd($cp->cart_value) }})
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="custom-card">
                        <h4 style="margin-top:0;font-weight:bold;border-bottom:2px solid #111;padding-bottom:10px;">TÓM TẮT ĐƠN HÀNG</h4>
                        <table class="table">
                            <tr>
                                <td>Tạm tính:</td>
                                <td class="text-right"><strong>{{ vnd($subtotal) }}</strong></td>
                            </tr>
                            <tr>
                                <td>Giảm giá Voucher {{ $coupon ? '(' . $coupon['code'] . ')' : '' }}:</td>
                                <td class="text-right" style="color:#16a34a;"><strong>-{{ vnd($discount) }}</strong></td>
                            </tr>
                            <tr>
                                <td>Thuế VAT (10%):</td>
                                <td class="text-right">{{ vnd($tax) }}</td>
                            </tr>
                            <tr style="font-size:18px;border-top:2px solid #111;">
                                <td><strong>Tổng thanh toán:</strong></td>
                                <td class="text-right" style="color:#e11d48;"><strong>{{ vnd($total) }}</strong></td>
                            </tr>
                        </table>
                        <a href="{{ route('checkout.index') }}" class="btn btn-danger btn-lg btn-block" style="background:#ff5722;border:none;font-weight:bold;padding:12px;">
                            TIẾN HÀNH ĐẶT HÀNG →
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
