@extends('layouts.admin')

@section('title', 'Báo Cáo Doanh Thu & Bán Hàng | Admin')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Báo Cáo Doanh Thu & Sản Phẩm Bán Chạy</h3>
    <button onclick="window.print()" class="tf-button style-1">🖨️ In Báo Cáo / Xuất PDF</button>
</div>

<div class="wg-box mb-30">
    <h5 class="mb-15">Top Sản Phẩm Bán Chạy Nhất</h5>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Mã SKU</th>
                    <th class="text-center">Tổng số lượng đã bán</th>
                    <th class="text-center">Tổng doanh thu mang lại</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topProducts as $tp)
                <tr>
                    <td><strong>{{ $tp->product->name ?? 'Sản phẩm #'.$tp->product_id }}</strong></td>
                    <td>{{ $tp->product->sku ?? '-' }}</td>
                    <td class="text-center">{{ $tp->total_qty }}</td>
                    <td class="text-center"><strong style="color:#16a34a;">{{ vnd($tp->total_revenue) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="wg-box">
    <h5 class="mb-15">Sổ Cái Chi Tiết Doanh Thu Theo Đơn Hàng</h5>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Mã ĐH</th>
                    <th>Ngày đặt</th>
                    <th>Khách hàng</th>
                    <th>Phương thức TT</th>
                    <th>Trạng thái đơn</th>
                    <th>Doanh thu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $ord)
                <tr>
                    <td><strong>{{ $ord->order_code }}</strong></td>
                    <td>{{ $ord->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $ord->name }}</td>
                    <td>{{ strtoupper($ord->payment_mode) }}</td>
                    <td>
                        @if($ord->status == 'ordered') <span class="badge bg-secondary">Mới đặt</span>
                        @elseif($ord->status == 'processing') <span class="badge bg-info">Đang đóng gói</span>
                        @elseif($ord->status == 'shipping') <span class="badge bg-primary">Đang giao</span>
                        @elseif($ord->status == 'delivered') <span class="badge bg-success">Đã giao</span>
                        @else <span class="badge bg-danger">Đã hủy</span>
                        @endif
                    </td>
                    <td><strong style="color:#e11d48;">{{ vnd($ord->total) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
