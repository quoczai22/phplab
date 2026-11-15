@extends('layouts.admin')

@section('title', 'Thêm Banner Mới | Admin')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Thêm Banner Trình Chiếu Mới</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Bảng Điều Khiển</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><a href="{{ route('admin.sliders') }}"><div class="text-tiny">Banner & Slider</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Thêm mới</div></li>
    </ul>
</div>

<div class="wg-box">
    <form class="form-new-product form-style-1" action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <fieldset class="name">
            <div class="body-title">Khẩu hiệu (Tagline) <span class="tf-color-1">*</span></div>
            <input class="flex-grow" type="text" placeholder="Khẩu hiệu ngắn (VD: Bộ Sưu Tập Mới)" name="tagline" required>
        </fieldset>
        <fieldset class="name">
            <div class="body-title">Tiêu đề chính <span class="tf-color-1">*</span></div>
            <input class="flex-grow" type="text" placeholder="Tiêu đề chính (VD: Thời Trang Mùa Hè 2026)" name="title" required>
        </fieldset>
        <fieldset class="name">
            <div class="body-title">Tiêu đề phụ <span class="tf-color-1">*</span></div>
            <input class="flex-grow" type="text" placeholder="Tiêu đề phụ mô tả ngắn..." name="subtitle" required>
        </fieldset>
        <fieldset class="name">
            <div class="body-title">Liên kết khi click</div>
            <input class="flex-grow" type="text" placeholder="/collection" name="link" value="/collection">
        </fieldset>
        <fieldset>
            <div class="body-title">Ảnh banner</div>
            <div class="upload-image flex-grow">
                <div class="item up-load">
                    <label class="uploadfile" for="myFile">
                        <span class="icon"><i class="icon-upload-cloud"></i></span>
                        <span class="body-text">Chọn ảnh banner <span class="tf-color">từ thiết bị</span></span>
                        <input type="file" id="myFile" name="image" accept="image/*">
                    </label>
                </div>
            </div>
        </fieldset>
        <div class="bot">
            <div></div>
            <button class="tf-button w208" type="submit">Lưu Banner</button>
        </div>
    </form>
</div>
@endsection
