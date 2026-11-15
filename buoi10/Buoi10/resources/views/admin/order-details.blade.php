@extends('layouts.admin')

@section('title', 'Chi Tiết Đơn Hàng ' . $order->order_code . ' | Admin')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Chi Tiết Đơn Hàng: {{ $order->order_code }}</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Bảng Điều Khiển</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><a href="{{ route('admin.orders') }}"><div class="text-tiny">Đơn Hàng</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">{{ $order->order_code }}</div></li>
    </ul>
</div>

<div class="wg-box">
    <div class="flex items-center justify-between gap10 flex-wrap">
        <div class="wg-filter flex-grow">
            <h5>Danh Sách Mặt Hàng Đã Đặt</h5>
        </div>
        <a class="tf-button style-1 w208" href="{{ route('admin.orders') }}">← Quay lại</a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th class="text-center">Đơn giá</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-center">Mã SKU</th>
                    <th class="text-center">Danh mục</th>
                    <th class="text-center">Thương hiệu</th>
                    <th class="text-center">Phân loại</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td class="pname">
                        <div class="image">
                            <img src="{{ asset($item->product->image ?? 'frontend/images/pi.jpg') }}" alt="" class="image">
                        </div>
                        <div class="name">
                            <a href="{{ $item->product ? route('product.single', $item->product->slug) : '#' }}" target="_blank" class="body-title-2">
                                {{ $item->product->name ?? 'Sản phẩm #'.$item->product_id }}
                            </a>
                        </div>
                    </td>
                    <td class="text-center">{{ vnd($item->price) }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-center">{{ $item->product->sku ?? '-' }}</td>
                    <td class="text-center">{{ $item->product->category->name ?? '-' }}</td>
                    <td class="text-center">{{ $item->product->brand->name ?? '-' }}</td>
                    <td class="text-center">{{ $item->options }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="wg-box mt-5">
    <h5>Địa Chỉ Nhận Hàng Của Khách</h5>
    <div class="my-account__address-item col-md-6" style="padding-left:0;margin-top:10px;">
        <div class="my-account__address-item__detail">
            <p><strong>Người nhận: {{ $order->name }}</strong></p>
            <p>Địa chỉ: {{ $order->address }}</p>
            <p>Tỉnh/Thành phố: {{ $order->city }}</p>
            <p>Số điện thoại: <strong>{{ $order->phone }}</strong></p>
        </div>
    </div>
</div>

<div class="wg-box mt-5">
    <h5>Thông Tin Thanh Toán & Cập Nhật Tiến Độ</h5>
    <table class="table table-striped table-bordered table-transaction">
        <tbody>
            <tr>
                <th>Tạm tính</th>
                <td>{{ vnd($order->subtotal) }}</td>
                <th>Thuế VAT</th>
                <td>{{ vnd($order->tax) }}</td>
                <th>Giảm giá Voucher</th>
                <td style="color:#16a34a;">-{{ vnd($order->discount) }}</td>
            </tr>
            <tr>
                <th>Tổng thanh toán</th>
                <td><strong style="color:#e11d48;font-size:16px;">{{ vnd($order->total) }}</strong></td>
                <th>Hình thức TT</th>
                <td>{{ strtoupper($order->payment_mode) }} ({{ $order->payment_status == 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }})</td>
                <th>Trạng thái đơn</th>
                <td>
                    <strong>
                        @if($order->status == 'ordered') Mới đặt
                        @elseif($order->status == 'processing') Đang đóng gói
                        @elseif($order->status == 'shipping') Đang giao
                        @elseif($order->status == 'delivered') Đã giao thành công
                        @else Đã hủy
                        @endif
                    </strong>
                </td>
            </tr>
            <tr>
                <th>Thời gian đặt</th>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <th>Thời gian giao</th>
                <td>{{ $order->delivered_date ?: '-' }}</td>
                <th>Thời gian hủy</th>
                <td>{{ $order->canceled_date ?: '-' }}</td>
            </tr>
        </tbody>
    </table>

    <hr>
    <h5 class="mb-10">Cập nhật trạng thái đơn hàng & Phân công Shipper</h5>
    <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="form-style-1">
        @csrf
        <div class="cols gap22">
            <fieldset>
                <div class="body-title mb-10">Trạng thái đơn hàng</div>
                <div class="select">
                    <select name="status">
                        <option value="ordered" {{ $order->status == 'ordered' ? 'selected' : '' }}>Mới đặt (Chờ xử lý)</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang đóng gói hàng</option>
                        <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Đang vận chuyển giao hàng</option>
                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Đã giao hàng thành công</option>
                        <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>Đã hủy đơn</option>
                    </select>
                </div>
            </fieldset>
            <fieldset>
                <div class="body-title mb-10">Trạng thái thanh toán</div>
                <div class="select">
                    <select name="payment_status">
                        <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Chưa thanh toán</option>
                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Đã hoàn tất thanh toán</option>
                        <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền lại cho khách</option>
                    </select>
                </div>
            </fieldset>
        </div>
        <div class="cols gap22 mt-15">
            <fieldset>
                <div class="body-title mb-10">Tên nhân viên Shipper phụ trách</div>
                <input type="text" name="shipper_name" value="{{ $order->shipper_name ?: 'Nguyễn Văn Nhân Viên' }}" placeholder="Tên nhân viên giao hàng">
            </fieldset>
            <fieldset>
                <div class="body-title mb-10">Số điện thoại Shipper</div>
                <input type="text" name="shipper_phone" value="{{ $order->shipper_phone ?: '0902345678' }}" placeholder="Số điện thoại">
            </fieldset>
        </div>
        <fieldset class="mt-15">
            <div class="body-title mb-10">Ghi chú hành trình đơn hàng</div>
            <input type="text" name="tracking_note" value="{{ $order->tracking_note }}" placeholder="Ví dụ: Đang trên đường giao đến số nhà...">
        </fieldset>
        <div class="mt-20">
            <button type="submit" class="tf-button w208">Lưu Cập Nhật</button>
        </div>
    </form>
</div>
@endsection
