@extends('layouts.admin')

@section('title', 'Quản Lý Mã Giảm Giá | Admin')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Mã Giảm Giá & Voucher ({{ $coupons->count() }})</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Bảng Điều Khiển</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Mã giảm giá</div></li>
    </ul>
</div>

<div class="wg-box">
    <div class="flex items-center justify-between gap10 flex-wrap">
        <div class="wg-filter flex-grow">
            <h5>Danh Sách Mã Giảm Giá Đang Hoạt Động</h5>
        </div>
        <a class="tf-button style-1 w208" href="{{ route('admin.coupons.add') }}"><i class="icon-plus"></i>Tạo mã mới</a>
    </div>
    <div class="wg-table table-all-user">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Mã Voucher</th>
                        <th>Loại giảm giá</th>
                        <th>Mức giảm</th>
                        <th>Đơn tối thiểu</th>
                        <th>Hạn sử dụng</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coupons as $cp)
                    <tr>
                        <td>{{ $cp->id }}</td>
                        <td><strong>{{ $cp->code }}</strong></td>
                        <td>{{ $cp->type == 'percent' ? 'Theo tỷ lệ %' : 'Số tiền cố định' }}</td>
                        <td><strong style="color:#e11d48;">{{ $cp->type == 'percent' ? $cp->value . '%' : vnd($cp->value) }}</strong></td>
                        <td>{{ vnd($cp->cart_value) }}</td>
                        <td>{{ $cp->expiry_date }}</td>
                        <td>
                            <div class="list-icon-function">
                                <form action="{{ route('admin.coupons.delete', $cp->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="item text-danger delete" style="border:none;background:none;" onclick="return confirm('Bạn có chắc muốn xóa mã giảm giá này?')">
                                        <i class="icon-trash-2"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
