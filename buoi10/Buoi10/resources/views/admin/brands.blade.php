@extends('layouts.admin')

@section('title', 'Quản Lý Thương Hiệu | Admin')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Thương Hiệu Thời Trang ({{ $brands->count() }})</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Bảng Điều Khiển</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Thương hiệu</div></li>
    </ul>
</div>

<div class="wg-box">
    <div class="flex items-center justify-between gap10 flex-wrap">
        <div class="wg-filter flex-grow">
            <form class="form-search" action="{{ route('admin.brands') }}" method="GET">
                <fieldset class="name">
                    <input type="text" placeholder="Tìm kiếm tên thương hiệu..." name="name" value="{{ request('name') }}">
                </fieldset>
                <div class="button-submit">
                    <button type="submit"><i class="icon-search"></i></button>
                </div>
            </form>
        </div>
        <a class="tf-button style-1 w208" href="{{ route('admin.brands.add') }}"><i class="icon-plus"></i>Thêm thương hiệu mới</a>
    </div>
    <div class="wg-table table-all-user">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên thương hiệu</th>
                    <th>Đường dẫn tĩnh (Slug)</th>
                    <th>Số sản phẩm</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($brands as $brand)
                <tr>
                    <td>{{ $brand->id }}</td>
                    <td class="pname">
                        <div class="image">
                            <img src="{{ asset($brand->image ?: 'frontend/images/logo.png') }}" alt="" class="image">
                        </div>
                        <div class="name">
                            <a href="{{ route('collection', ['brand' => $brand->slug]) }}" target="_blank" class="body-title-2">{{ $brand->name }}</a>
                        </div>
                    </td>
                    <td>{{ $brand->slug }}</td>
                    <td><strong>{{ $brand->products_count }}</strong> sản phẩm</td>
                    <td>
                        <div class="list-icon-function">
                            <form action="{{ route('admin.brands.delete', $brand->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="item text-danger delete" style="border:none;background:none;" onclick="return confirm('Bạn có chắc muốn xóa thương hiệu này?')">
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
@endsection
