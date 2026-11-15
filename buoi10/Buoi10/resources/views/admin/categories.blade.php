@extends('layouts.admin')

@section('title', 'Quản Lý Danh Mục | Admin')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Danh Mục Sản Phẩm ({{ $categories->count() }})</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Bảng Điều Khiển</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Danh mục</div></li>
    </ul>
</div>

<div class="wg-box">
    <div class="flex items-center justify-between gap10 flex-wrap">
        <div class="wg-filter flex-grow">
            <form class="form-search" action="{{ route('admin.categories') }}" method="GET">
                <fieldset class="name">
                    <input type="text" placeholder="Tìm kiếm tên danh mục..." name="name" value="{{ request('name') }}">
                </fieldset>
                <div class="button-submit">
                    <button type="submit"><i class="icon-search"></i></button>
                </div>
            </form>
        </div>
        <a class="tf-button style-1 w208" href="{{ route('admin.categories.add') }}"><i class="icon-plus"></i>Thêm danh mục mới</a>
    </div>
    <div class="wg-table table-all-user">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên danh mục</th>
                    <th>Đường dẫn tĩnh (Slug)</th>
                    <th>Số sản phẩm</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $cat)
                <tr>
                    <td>{{ $cat->id }}</td>
                    <td class="pname">
                        <div class="image">
                            <img src="{{ asset($cat->image ?: 'frontend/images/women.jpg') }}" alt="" class="image">
                        </div>
                        <div class="name">
                            <a href="{{ route('collection', ['category' => $cat->slug]) }}" target="_blank" class="body-title-2">{{ $cat->name }}</a>
                        </div>
                    </td>
                    <td>{{ $cat->slug }}</td>
                    <td><a href="{{ route('collection', ['category' => $cat->slug]) }}" target="_blank"><strong>{{ $cat->products_count }}</strong> sản phẩm</a></td>
                    <td>
                        <div class="list-icon-function">
                            <form action="{{ route('admin.categories.delete', $cat->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="item text-danger delete" style="border:none;background:none;" onclick="return confirm('Bạn có chắc muốn xóa danh mục này?')">
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
