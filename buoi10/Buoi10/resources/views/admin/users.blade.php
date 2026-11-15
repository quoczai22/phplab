@extends('layouts.admin')

@section('title', 'Quản Lý Tài Khoản & Phân Quyền | Admin')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Tài Khoản & Phân Quyền Người Dùng ({{ $users->count() }})</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Bảng Điều Khiển</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Người dùng</div></li>
    </ul>
</div>

<div class="wg-box">
    <div class="flex items-center justify-between gap10 flex-wrap">
        <div class="wg-filter flex-grow">
            <form class="form-search" action="{{ route('admin.users') }}" method="GET">
                <fieldset class="name">
                    <input type="text" placeholder="Tìm kiếm theo tên hoặc email người dùng..." name="name" value="{{ request('name') }}">
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
                        <th>#</th>
                        <th>Họ và tên</th>
                        <th>Số điện thoại</th>
                        <th>Email</th>
                        <th class="text-center">Vai trò hiện tại</th>
                        <th class="text-center">Số đơn hàng</th>
                        <th>Cập nhật quyền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                    <tr>
                        <td>{{ $u->id }}</td>
                        <td class="pname">
                            <div class="image">
                                <img src="{{ asset('admin/images/avatar/user-1.png') }}" alt="" class="image">
                            </div>
                            <div class="name">
                                <a href="#" class="body-title-2">{{ $u->name }}</a>
                                <div class="text-tiny mt-3">
                                    {{ $u->role == 'admin' ? 'Quản trị viên' : ($u->role == 'staff' ? 'Nhân viên' : 'Khách hàng') }}
                                </div>
                            </div>
                        </td>
                        <td>{{ $u->phone }}</td>
                        <td>{{ $u->email }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $u->role == 'admin' ? 'danger' : ($u->role == 'staff' ? 'info' : 'success') }}">
                                {{ $u->role == 'admin' ? 'Quản Trị Viên' : ($u->role == 'staff' ? 'Nhân Viên' : 'Khách Hàng') }}
                            </span>
                        </td>
                        <td class="text-center"><strong>{{ $u->orders_count }}</strong></td>
                        <td>
                            <form action="{{ route('admin.users.role', $u->id) }}" method="POST" class="flex items-center gap10">
                                @csrf
                                <select name="role" style="padding:6px;border-radius:4px;">
                                    <option value="customer" {{ $u->role == 'customer' ? 'selected' : '' }}>Khách Hàng</option>
                                    <option value="staff" {{ $u->role == 'staff' ? 'selected' : '' }}>Nhân Viên (Shipper)</option>
                                    <option value="admin" {{ $u->role == 'admin' ? 'selected' : '' }}>Quản Trị Viên (Admin)</option>
                                </select>
                                <button type="submit" class="tf-button">Lưu</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
