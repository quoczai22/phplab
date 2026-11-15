@extends('layouts.admin')

@section('title', 'Quản Lý Đổi / Trả Hàng | Admin')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Quản Lý Yêu Cầu Đổi / Trả Hàng</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Bảng Điều Khiển</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Đổi / Trả hàng</div></li>
    </ul>
</div>

<div class="wg-box">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Mã Đơn Hàng</th>
                    <th>Khách Hàng</th>
                    <th>Hình Thức</th>
                    <th>Lý Do Khách Hàng</th>
                    <th>Trạng Thái</th>
                    <th>Xử Lý & Phản Hồi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($returns as $ret)
                <tr>
                    <td>{{ $ret->id }}</td>
                    <td><strong>{{ $ret->order->order_code ?? '#' }}</strong></td>
                    <td>{{ $ret->user->name ?? 'Khách hàng' }}</td>
                    <td>
                        <span class="badge bg-{{ $ret->type == 'exchange' ? 'info' : 'warning' }}">
                            {{ $ret->type == 'exchange' ? 'Đổi Size/Màu' : 'Trả hàng & Hoàn tiền' }}
                        </span>
                    </td>
                    <td>{{ $ret->reason }}</td>
                    <td>
                        @if($ret->status == 'pending') <span class="badge bg-warning">Chờ duyệt</span>
                        @elseif($ret->status == 'approved') <span class="badge bg-info">Đã duyệt</span>
                        @elseif($ret->status == 'completed') <span class="badge bg-success">Hoàn tất</span>
                        @else <span class="badge bg-danger">Từ chối</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.returns.update', $ret->id) }}" method="POST" class="flex items-center gap10">
                            @csrf
                            <select name="status" style="padding:6px;border-radius:4px;">
                                <option value="pending" {{ $ret->status == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                <option value="approved" {{ $ret->status == 'approved' ? 'selected' : '' }}>Duyệt yêu cầu</option>
                                <option value="completed" {{ $ret->status == 'completed' ? 'selected' : '' }}>Hoàn tất xử lý</option>
                                <option value="rejected" {{ $ret->status == 'rejected' ? 'selected' : '' }}>Từ chối yêu cầu</option>
                            </select>
                            <input type="text" name="admin_note" value="{{ $ret->admin_note }}" placeholder="Ghi chú phản hồi cho khách..." style="width:200px;padding:6px;">
                            <button type="submit" class="tf-button">Lưu</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
