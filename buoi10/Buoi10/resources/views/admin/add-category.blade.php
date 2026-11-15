@extends('layouts.admin')

@section('title', 'Thêm Danh Mục Mới | Admin')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Thêm Danh Mục Sản Phẩm Mới</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Bảng Điều Khiển</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><a href="{{ route('admin.categories') }}"><div class="text-tiny">Danh mục</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Thêm mới</div></li>
    </ul>
</div>

<div class="wg-box">
    <form class="form-new-product form-style-1" action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <fieldset class="name">
            <div class="body-title">Tên danh mục <span class="tf-color-1">*</span></div>
            <input class="flex-grow" type="text" placeholder="Nhập tên danh mục (VD: Đầm & Váy Dự Tiệc)..." name="name" required>
        </fieldset>
        <fieldset class="name">
            <div class="body-title">Đường dẫn tĩnh (Slug)</div>
            <input class="flex-grow" type="text" placeholder="Tự động tạo theo tên nếu để trống" name="slug">
        </fieldset>
        <fieldset>
            <div class="body-title">Ảnh đại diện danh mục</div>
            <div class="upload-image flex-grow">
                <div id="upload-file" class="item up-load">
                    <label class="uploadfile" for="myFile">
                        <span class="icon"><i class="icon-upload-cloud"></i></span>
                        <span class="body-text">Chọn ảnh <span class="tf-color">từ thiết bị</span></span>
                        <input type="file" id="myFile" name="image" accept="image/*">
                    </label>
                </div>
            </div>
        </fieldset>
        <div class="bot">
            <div></div>
            <button class="tf-button w208" type="submit">Lưu Danh Mục</button>
        </div>
    </form>
</div>
@endsection
