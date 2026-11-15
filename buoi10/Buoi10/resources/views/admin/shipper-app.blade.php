@extends('layouts.app')

@section('title', 'Ứng Dụng Giao Hàng Shipper | Fashion Store')

@section('content')
<div class="content" style="background:#f1f5f9;padding:30px 0;">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div style="background:#fff;border:8px solid #1e293b;border-radius:28px;overflow:hidden;box-shadow:0 15px 35px rgba(0,0,0,0.2);">
                    <div style="background:#1e293b;color:#fff;padding:15px 20px;">
                        <span class="pull-right" style="font-size:12px;color:#4ade80;">● GPS Trực tuyến</span>
                        <h4 style="margin:0;font-weight:bold;">📱 FASHION SHIPPER APP v2.4</h4>
                        <small style="color:#94a3b8;">Nhân viên giao hàng: {{ auth()->check() ? auth()->user()->name : 'Nguyễn Văn Nhân Viên (0902345678)' }}</small>
                    </div>

                    <div style="padding:18px;max-height:680px;overflow-y:auto;">
                        <h5 style="font-weight:bold;margin-top:0;">Danh sách Vận đơn cần giao ({{ $orders->count() }} đơn)</h5>

                        @foreach($orders as $order)
                        <div style="border:1px solid #cbd5e1;border-radius:12px;padding:14px;margin-bottom:15px;background:{{ $order->status == 'delivered' ? '#f0fdf4' : '#fff' }};">
                            <div class="clearfix">
                                <strong style="font-size:15px;color:#0f172a;">📦 {{ $order->order_code }}</strong>
                                <span class="label label-{{ $order->status == 'delivered' ? 'success' : 'info' }} pull-right">
                                    @if($order->status == 'ordered') Mới đặt
                                    @elseif($order->status == 'processing') Đang đóng gói
                                    @elseif($order->status == 'shipping') Đang giao
                                    @elseif($order->status == 'delivered') Đã giao xong
                                    @else Đã hủy
                                    @endif
                                </span>
                            </div>
                            <div style="margin-top:8px;font-size:13px;color:#334155;">
                                <div>👤 Người nhận: <strong>{{ $order->name }}</strong> - <a href="tel:{{ $order->phone }}">📞 {{ $order->phone }}</a></div>
                                <div>📍 Địa chỉ: {{ $order->address }}, {{ $order->city }}</div>
                                <div>💰 Tiền thu hộ: <strong style="color:#e11d48;">{{ $order->payment_status == 'paid' ? '0 đ (Đã TT online)' : vnd($order->total) }}</strong></div>
                                <div>📝 Ghi chú: <em>{{ $order->tracking_note }}</em></div>
                            </div>

                            <form action="{{ route('shipper.order.update', $order->id) }}" method="POST" style="margin-top:10px;border-top:1px dashed #e2e8f0;padding-top:10px;">
                                @csrf
                                <div class="row">
                                    <div class="col-xs-5">
                                        <select name="status" class="form-control input-sm">
                                            <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>🚚 Đang giao</option>
                                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>✔ Đã giao xong</option>
                                            <option value="canceled">✖ Khách hẹn lại / Hủy</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-4">
                                        <input type="text" name="tracking_note" class="form-control input-sm" placeholder="Vị trí/Ghi chú..." value="Đã giao tận tay khách hàng">
                                    </div>
                                    <div class="col-xs-3">
                                        <button type="submit" class="btn btn-success btn-sm btn-block">Cập nhật</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
