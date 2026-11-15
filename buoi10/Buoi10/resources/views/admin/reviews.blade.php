@extends('layouts.admin')

@section('title', 'Quản Lý Đánh Giá Sản Phẩm | Admin')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Đánh Giá & Nhận Xét Của Khách Hàng ({{ $reviews->count() }})</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Bảng Điều Khiển</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Đánh giá sản phẩm</div></li>
    </ul>
</div>

<div class="wg-box">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Sản Phẩm</th>
                    <th>Khách Hàng</th>
                    <th>Số Sao</th>
                    <th>Nội Dung Nhận Xét</th>
                    <th>Ngày Đánh Giá</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $rev)
                <tr>
                    <td>{{ $rev->id }}</td>
                    <td>
                        @if($rev->product)
                            <a href="{{ route('product.single', $rev->product->slug) }}" target="_blank" class="body-title-2">{{ $rev->product->name }}</a>
                        @else
                            #{{ $rev->product_id }}
                        @endif
                    </td>
                    <td>{{ $rev->user->name ?? 'Khách hàng' }}</td>
                    <td style="color:#f59e0b;font-weight:bold;">{{ $rev->rating }} ★</td>
                    <td>{{ $rev->comment }}</td>
                    <td>{{ $rev->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <form action="{{ route('admin.reviews.delete', $rev->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="item text-danger delete" style="border:none;background:none;" onclick="return confirm('Xóa nhận xét này?')">
                                <i class="icon-trash-2"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
