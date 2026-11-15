@extends('layouts.admin')

@section('title', 'Quản Lý Đơn Hàng | Admin')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Danh Sách Đơn Hàng ({{ $orders->count() }})</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Bảng Điều Khiển</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Tất cả đơn hàng</div></li>
    </ul>
</div>

<div class="wg-box">
    <div class="flex items-center justify-between gap10 flex-wrap">
        <div class="wg-filter flex-grow">
            <form class="form-search" action="{{ route('admin.orders') }}" method="GET">
                <fieldset class="name">
                    <input type="text" placeholder="Tìm kiếm theo mã đơn hàng, tên khách hàng, số điện thoại..." name="name" value="{{ request('name') }}">
                </fieldset>
                <div class="button-submit">
                    <button type="submit"><i class="icon-search"></i></button>
                </div>
            </form>
        </div>
    </div>
    <div class="wg-table table-all-user">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width:120px">Mã ĐH</th>
                        <th class="text-center">Khách hàng</th>
                        <th class="text-center">Điện thoại</th>
                        <th class="text-center">Tạm tính</th>
                        <th class="text-center">Giảm giá</th>
                        <th class="text-center">VAT</th>
                        <th class="text-center">Tổng tiền</th>
                        <th class="text-center">Thanh toán</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center">Ngày đặt</th>
                        <th class="text-center">Số SP</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td class="text-center"><strong>{{ $order->order_code }}</strong></td>
                        <td class="text-center">{{ $order->name }}</td>
                        <td class="text-center">{{ $order->phone }}</td>
                        <td class="text-center">{{ vnd($order->subtotal) }}</td>
                        <td class="text-center" style="color:#16a34a;">-{{ vnd($order->discount) }}</td>
                        <td class="text-center">{{ vnd($order->tax) }}</td>
                        <td class="text-center"><strong style="color:#e11d48;">{{ vnd($order->total) }}</strong></td>
                        <td class="text-center">
                            <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                                {{ strtoupper($order->payment_mode) }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($order->status == 'ordered') <span class="badge bg-secondary">Mới đặt</span>
                            @elseif($order->status == 'processing') <span class="badge bg-info">Đang đóng gói</span>
                            @elseif($order->status == 'shipping') <span class="badge bg-primary">Đang giao</span>
                            @elseif($order->status == 'delivered') <span class="badge bg-success">Đã giao</span>
                            @else <span class="badge bg-danger">Đã hủy</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-center">{{ $order->items->sum('quantity') }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.orders.detail', $order->id) }}">
                                <div class="list-icon-function view-icon">
                                    <div class="item eye">
                                        <i class="icon-eye"></i>
                                    </div>
                                </div>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
