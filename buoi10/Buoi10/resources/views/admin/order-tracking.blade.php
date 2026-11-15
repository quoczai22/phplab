@extends('layouts.admin')

@section('title', 'Theo Dõi Vận Đơn | Admin')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Tra Cứu & Theo Dõi Vận Đơn</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Bảng Điều Khiển</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Theo dõi vận đơn</div></li>
    </ul>
</div>

<div class="wg-box mb-20">
    <form action="{{ route('admin.order.tracking') }}" method="GET" class="flex items-center gap10">
        <input type="text" name="order_code" placeholder="Nhập mã đơn hàng (VD: ORD-2026002)" value="{{ request('order_code', $order->order_code ?? '') }}" style="max-width:350px;">
        <button type="submit" class="tf-button">Tra cứu vận đơn</button>
    </form>
</div>

@if($order)
<div class="wg-box">
    <h4>Mã vận đơn: {{ $order->order_code }} - Khách hàng: {{ $order->name }} ({{ $order->phone }})</h4>
    <p class="mt-10">Địa chỉ giao hàng: {{ $order->address }}, {{ $order->city }}</p>
    <p>Trạng thái hiện tại: 
        <span class="badge bg-primary">
            @if($order->status == 'ordered') Mới đặt
            @elseif($order->status == 'processing') Đang đóng gói
            @elseif($order->status == 'shipping') Đang giao
            @elseif($order->status == 'delivered') Đã giao thành công
            @else Đã hủy
            @endif
        </span> 
        | Shipper phụ trách: <strong>{{ $order->shipper_name ?: 'Chưa phân công' }}</strong>
    </p>
    <p>Ghi chú hành trình: <strong>{{ $order->tracking_note }}</strong></p>
    <div class="mt-15">
        <a href="{{ route('admin.orders.detail', $order->id) }}" class="tf-button style-1">Xem Chi Tiết & Điều Phối</a>
    </div>
</div>
@endif
@endsection
