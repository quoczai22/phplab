@extends('layouts.app')

@section('title', 'Tài Khoản & Đơn Hàng Của Tôi | Fashion Store')

@section('content')
<div class="content">
    <div class="container" style="margin-top:25px;margin-bottom:40px;">
        <h3 style="font-weight:bold;margin-bottom:20px;color:#111;">
            👤 TÀI KHOẢN CỦA TÔI
            <span class="badge" style="background:#ff5722;font-size:12px;vertical-align:middle;margin-left:8px;">
                {{ $user->role == 'admin' ? 'Quản trị viên' : ($user->role == 'staff' ? 'Nhân viên giao vận' : 'Thành viên') }}
            </span>
        </h3>

        <div class="row">
            <!-- Cột trái: Thông tin tài khoản, Sổ địa chỉ, Đổi mật khẩu -->
            <div class="col-md-5">
                <div class="custom-card">
                    <h4 style="margin-top:0;font-weight:bold;">Thông tin cá nhân</h4>
                    <form action="{{ route('account.update') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Họ và tên</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                        </div>
                        <div class="form-group">
                            <label>Địa chỉ Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                        </div>
                        <button type="submit" class="btn btn-primary" style="background:#ff5722;border:none;">Lưu Thông Tin</button>
                    </form>
                </div>

                <!-- Sổ địa chỉ cá nhân -->
                <div class="custom-card">
                    <h4 style="margin-top:0;font-weight:bold;">📍 Sổ địa chỉ nhận hàng</h4>
                    @foreach($addresses as $addr)
                        <div style="border:1px solid #e2e8f0;padding:10px;border-radius:6px;margin-bottom:10px;background:{{ $addr->is_default ? '#f0fdf4' : '#fff' }};">
                            <strong>{{ $addr->name }}</strong> ({{ $addr->phone }})
                            @if($addr->is_default)
                                <span class="label label-success pull-right">Mặc định</span>
                            @endif
                            <div style="font-size:13px;color:#555;margin-top:4px;">
                                {{ $addr->address }}, {{ $addr->district ? $addr->district . ', ' : '' }}{{ $addr->city }}
                            </div>
                            <div style="margin-top:6px;">
                                @if(!$addr->is_default)
                                    <a href="{{ route('account.address.default', $addr->id) }}" class="btn btn-xs btn-default">Đặt mặc định</a>
                                @endif
                                <a href="{{ route('account.address.delete', $addr->id) }}" class="btn btn-xs btn-danger">Xóa</a>
                            </div>
                        </div>
                    @endforeach

                    <hr>
                    <h5>Thêm địa chỉ mới</h5>
                    <form action="{{ route('account.address.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="text" name="name" class="form-control input-sm" placeholder="Họ tên người nhận *" value="{{ $user->name }}" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="phone" class="form-control input-sm" placeholder="Số điện thoại *" value="{{ $user->phone }}" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="address" class="form-control input-sm" placeholder="Số nhà, tên đường, Phường/Xã *" required>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <input type="text" name="district" class="form-control input-sm" placeholder="Quận/Huyện">
                            </div>
                            <div class="col-xs-6">
                                <input type="text" name="city" class="form-control input-sm" placeholder="Tỉnh/Thành phố *" required>
                            </div>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="is_default" value="1"> Đặt làm địa chỉ mặc định</label>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm">+ Thêm Địa Chỉ</button>
                    </form>
                </div>

                <!-- Đổi mật khẩu -->
                <div class="custom-card">
                    <h4 style="margin-top:0;font-weight:bold;">🔒 Đổi mật khẩu</h4>
                    <form action="{{ route('account.password') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="password" name="current_password" class="form-control input-sm" placeholder="Mật khẩu hiện tại" required>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control input-sm" placeholder="Mật khẩu mới (tối thiểu 6 ký tự)" required>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password_confirmation" class="form-control input-sm" placeholder="Xác nhận lại mật khẩu mới" required>
                        </div>
                        <button type="submit" class="btn btn-warning btn-sm">Cập Nhật Mật Khẩu</button>
                    </form>
                </div>
            </div>

            <!-- Cột phải: Lịch sử đơn hàng, Đổi trả & Thông báo -->
            <div class="col-md-7">
                <!-- Lịch sử đơn hàng -->
                <div class="custom-card">
                    <h4 style="margin-top:0;font-weight:bold;">📦 Đơn hàng của bạn ({{ $orders->count() }})</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Mã ĐH</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>Thanh toán</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $ord)
                                <tr>
                                    <td><strong>{{ $ord->order_code }}</strong></td>
                                    <td>{{ $ord->created_at->format('d/m/Y') }}</td>
                                    <td style="color:#e11d48;font-weight:bold;">{{ vnd($ord->total) }}</td>
                                    <td>
                                        <span class="label label-{{ $ord->payment_status == 'paid' ? 'success' : 'warning' }}">
                                            {{ strtoupper($ord->payment_mode) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($ord->status == 'ordered') <span class="label label-default">Mới đặt</span>
                                        @elseif($ord->status == 'processing') <span class="label label-info">Đang đóng gói</span>
                                        @elseif($ord->status == 'shipping') <span class="label label-primary">Đang giao</span>
                                        @elseif($ord->status == 'delivered') <span class="label label-success">Đã giao</span>
                                        @else <span class="label label-danger">Đã hủy</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('account.order.detail', $ord->order_code) }}" class="btn btn-xs btn-primary">Chi tiết</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center" style="padding:20px;">Bạn chưa có đơn hàng nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Lịch sử đổi / trả hàng -->
                <div class="custom-card">
                    <h4 style="margin-top:0;font-weight:bold;">🔄 Lịch sử Đổi / Trả hàng ({{ $returnRequests->count() }})</h4>
                    @forelse($returnRequests as $ret)
                        <div style="border-left:4px solid #f59e0b;padding:10px;background:#fffbeb;margin-bottom:10px;">
                            <strong>Đơn hàng: {{ $ret->order->order_code ?? '#' }}</strong> -
                            Hình thức: <strong>{{ $ret->type == 'exchange' ? 'Đổi Size/Màu' : 'Trả hàng & Hoàn tiền' }}</strong>
                            <span class="label label-primary pull-right">
                                @if($ret->status == 'pending') Chờ duyệt
                                @elseif($ret->status == 'approved') Đã duyệt
                                @elseif($ret->status == 'completed') Hoàn tất
                                @else Bị từ chối
                                @endif
                            </span>
                            <p style="margin:5px 0 0;font-size:13px;">Lý do: {{ $ret->reason }}</p>
                            @if($ret->admin_note)
                                <small style="color:#b45309;display:block;margin-top:4px;">Phản hồi từ cửa hàng: {{ $ret->admin_note }}</small>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted">Chưa có yêu cầu đổi/trả hàng nào.</p>
                    @endforelse
                </div>

                <!-- Thông báo đơn hàng -->
                <div class="custom-card">
                    <h4 style="margin-top:0;font-weight:bold;">🔔 Thông báo cập nhật đơn hàng</h4>
                    @forelse($notifications as $noti)
                        <div style="border-bottom:1px solid #eee;padding:10px 0;">
                            <strong>{{ $noti->title }}</strong>
                            <small class="text-muted pull-right">{{ $noti->created_at->format('d/m/Y H:i') }}</small>
                            <div style="font-size:13px;color:#555;margin-top:3px;">{{ $noti->message }}</div>
                        </div>
                    @empty
                        <p class="text-muted">Chưa có thông báo nào.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
