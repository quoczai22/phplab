<div class="custom-card">
    <div class="clearfix" style="border-bottom:2px solid #eee;padding-bottom:12px;margin-bottom:20px;">
        <h4 class="pull-left" style="margin:0;font-weight:bold;">
            MÃ ĐƠN HÀNG: <span style="color:#e11d48;">{{ $order->order_code }}</span>
        </h4>
        <div class="pull-right">
            <span class="label label-primary" style="font-size:13px;">
                Trạng thái: 
                @if($order->status == 'ordered') Đã đặt hàng
                @elseif($order->status == 'processing') Đang đóng gói
                @elseif($order->status == 'shipping') Đang giao hàng
                @elseif($order->status == 'delivered') Đã giao thành công
                @else Đã hủy
                @endif
            </span>
            <span class="label label-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}" style="font-size:13px;margin-left:5px;">
                Thanh toán: {{ strtoupper($order->payment_mode) }} ({{ $order->payment_status == 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }})
            </span>
        </div>
    </div>

    <!-- Thanh tiến trình đơn hàng -->
    @php
        $steps = ['ordered' => 1, 'processing' => 2, 'shipping' => 3, 'delivered' => 4, 'canceled' => 0];
        $curStep = $steps[$order->status] ?? 1;
    @endphp
    @if($order->status == 'canceled')
        <div class="alert alert-danger" style="border-radius:6px;">Đơn hàng này đã bị hủy vào lúc {{ $order->canceled_date }}.</div>
    @else
        <div class="row text-center" style="margin-bottom:25px;">
            <div class="col-xs-3">
                <div style="padding:10px;border-radius:6px;background:{{ $curStep >= 1 ? '#dcfce7' : '#f1f5f9' }};border:1px solid {{ $curStep >= 1 ? '#22c55e' : '#cbd5e1' }};">
                    <strong>1. Đã Đặt Hàng</strong><br><small>{{ $order->created_at->format('d/m/Y H:i') }}</small>
                </div>
            </div>
            <div class="col-xs-3">
                <div style="padding:10px;border-radius:6px;background:{{ $curStep >= 2 ? '#dcfce7' : '#f1f5f9' }};border:1px solid {{ $curStep >= 2 ? '#22c55e' : '#cbd5e1' }};">
                    <strong>2. Đang Đóng Gói</strong><br><small>Kho chuẩn bị hàng</small>
                </div>
            </div>
            <div class="col-xs-3">
                <div style="padding:10px;border-radius:6px;background:{{ $curStep >= 3 ? '#dcfce7' : '#f1f5f9' }};border:1px solid {{ $curStep >= 3 ? '#22c55e' : '#cbd5e1' }};">
                    <strong>3. Đang Giao Hàng</strong><br><small>{{ $order->shipper_name ?: 'Đơn vị vận chuyển' }}</small>
                </div>
            </div>
            <div class="col-xs-3">
                <div style="padding:10px;border-radius:6px;background:{{ $curStep >= 4 ? '#dcfce7' : '#f1f5f9' }};border:1px solid {{ $curStep >= 4 ? '#22c55e' : '#cbd5e1' }};">
                    <strong>4. Giao Thành Công</strong><br><small>{{ $order->delivered_date ? date('d/m/Y', strtotime($order->delivered_date)) : 'Dự kiến 2-3 ngày' }}</small>
                </div>
            </div>
        </div>
    @endif

    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-6">
            <h5><strong>📍 Địa chỉ nhận hàng:</strong></h5>
            <p style="margin:0;">Người nhận: <strong>{{ $order->name }}</strong> ({{ $order->phone }})</p>
            <p style="margin:0;">Địa chỉ: {{ $order->address }}, {{ $order->city }}</p>
        </div>
        <div class="col-md-6">
            <h5><strong>🚚 Thông tin vận chuyển:</strong></h5>
            <p style="margin:0;">Nhân viên giao hàng: <strong>{{ $order->shipper_name ?: 'Đang điều phối' }}</strong> {{ $order->shipper_phone ? '(' . $order->shipper_phone . ')' : '' }}</p>
            <p style="margin:0;">Ghi chú hành trình: <span style="color:#0284c7;font-weight:bold;">{{ $order->tracking_note }}</span></p>
            @if($order->payment_status != 'paid' && in_array($order->payment_mode, ['vnpay', 'momo', 'banking']))
                <a href="{{ route('checkout.payment', $order->order_code) }}" class="btn btn-xs btn-warning" style="margin-top:6px;font-weight:bold;">💳 Thanh toán trực tuyến ngay</a>
            @endif
        </div>
    </div>

    <table class="table table-bordered">
        <thead style="background:#f8fafc;">
            <tr>
                <th>Sản phẩm</th>
                <th>Phân loại</th>
                <th class="text-center">Đơn giá</th>
                <th class="text-center">SL</th>
                <th class="text-right">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>
                    @if($item->product)
                        <a href="{{ route('product.single', $item->product->slug) }}" style="font-weight:bold;color:#111;">{{ $item->product->name }}</a>
                    @else
                        Sản phẩm #{{ $item->product_id }}
                    @endif
                </td>
                <td>{{ $item->options }}</td>
                <td class="text-center">{{ vnd($item->price) }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">{{ vnd($item->price * $item->quantity) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right">Tạm tính:</td>
                <td class="text-right">{{ vnd($order->subtotal) }}</td>
            </tr>
            <tr>
                <td colspan="4" class="text-right">Giảm giá Voucher:</td>
                <td class="text-right" style="color:#16a34a;">-{{ vnd($order->discount) }}</td>
            </tr>
            <tr>
                <td colspan="4" class="text-right">Thuế VAT (10%):</td>
                <td class="text-right">{{ vnd($order->tax) }}</td>
            </tr>
            <tr style="font-size:16px;font-weight:bold;">
                <td colspan="4" class="text-right">Tổng thanh toán:</td>
                <td class="text-right" style="color:#e11d48;">{{ vnd($order->total) }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Form yêu cầu Đổi / Trả hàng -->
    @auth
    <hr>
    <div style="background:#fffbeb;padding:15px;border:1px solid #fde68a;border-radius:6px;">
        <h4 style="margin-top:0;color:#b45309;font-weight:bold;">🔄 GỬI YÊU CẦU ĐỔI / TRẢ HÀNG</h4>
        <form action="{{ route('account.order.return', $order->id) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Hình thức yêu cầu:</label>
                        <select name="type" class="form-control">
                            <option value="exchange">Đổi Size / Màu sắc</option>
                            <option value="return">Trả hàng & Hoàn tiền</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Lý do đổi / trả:</label>
                        <input type="text" name="reason" class="form-control" placeholder="Ví dụ: Cần đổi sang Size L hoặc muốn đổi màu khác..." required>
                    </div>
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-warning btn-block" style="font-weight:bold;">Gửi Yêu Cầu</button>
                </div>
            </div>
        </form>
    </div>
    @endauth
</div>
