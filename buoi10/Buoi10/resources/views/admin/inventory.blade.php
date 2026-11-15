@extends('layouts.admin')

@section('title', 'Quản Lý Biến Thể & Tồn Kho | Admin')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Quản Lý Biến Thể Sản Phẩm & Tồn Kho</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Bảng Điều Khiển</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Biến thể & Tồn kho</div></li>
    </ul>
</div>

@foreach($products as $product)
<div class="wg-box mb-20">
    <div class="flex items-center justify-between flex-wrap gap10">
        <div class="flex items-center gap14">
            <img src="{{ asset($product->image) }}" style="width:55px;height:65px;object-fit:cover;border-radius:6px;">
            <div>
                <h5 class="mb-1">{{ $product->name }} (SKU: {{ $product->sku }})</h5>
                <div class="text-tiny">Danh mục: {{ $product->category->name ?? '-' }} | Giá niêm yết: {{ vnd($product->effectivePrice()) }}</div>
            </div>
        </div>

        <form action="{{ route('admin.stock.update', $product->id) }}" method="POST" class="flex items-center gap10">
            @csrf
            <span class="body-title">Tổng tồn kho:</span>
            <input type="number" name="quantity" value="{{ $product->quantity }}" style="width:90px;padding:6px 10px;">
            <button type="submit" class="tf-button style-1">Cập nhật kho</button>
        </form>
    </div>

    <div class="table-responsive mt-15">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Mã SKU Biến thể</th>
                    <th>Kích cỡ (Size)</th>
                    <th>Màu sắc</th>
                    <th>Giá bán</th>
                    <th>Số lượng tồn</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($product->variants as $v)
                <tr>
                    <td>{{ $v->sku }}</td>
                    <td><strong>{{ $v->size }}</strong></td>
                    <td>{{ $v->color }}</td>
                    <td>{{ vnd($v->price ?: $product->effectivePrice()) }}</td>
                    <td>{{ $v->quantity }}</td>
                    <td>
                        <a href="{{ route('admin.variant.delete', $v->id) }}" class="text-danger" onclick="return confirm('Xóa biến thể này?')">Xóa</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <form action="{{ route('admin.variant.store', $product->id) }}" method="POST" class="flex items-center gap10 flex-wrap mt-10">
        @csrf
        <input type="text" name="size" placeholder="Kích cỡ (VD: XXL)" style="width:130px;" required>
        <input type="text" name="color" placeholder="Màu sắc (VD: Vàng)" style="width:140px;" required>
        <input type="number" step="1" name="price" placeholder="Giá bán (đ)" value="{{ $product->effectivePrice() }}" style="width:120px;">
        <input type="number" name="quantity" placeholder="Số lượng tồn" value="15" style="width:120px;" required>
        <button type="submit" class="tf-button">+ Thêm biến thể mới</button>
    </form>
</div>
@endforeach
@endsection
