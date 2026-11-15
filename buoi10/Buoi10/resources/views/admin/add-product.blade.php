@extends('layouts.admin')

@section('title', 'Thêm Sản Phẩm Mới | Admin')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Thêm Sản Phẩm Mới</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Bảng Điều Khiển</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><a href="{{ route('admin.products') }}"><div class="text-tiny">Sản phẩm</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Thêm mới</div></li>
    </ul>
</div>

<form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data" action="{{ route('admin.products.store') }}">
    @csrf
    <div class="wg-box">
        <fieldset class="name">
            <div class="body-title mb-10">Tên sản phẩm <span class="tf-color-1">*</span></div>
            <input class="mb-10" type="text" placeholder="Nhập tên sản phẩm..." name="name" required>
        </fieldset>

        <fieldset class="name">
            <div class="body-title mb-10">Đường dẫn tĩnh (Slug)</div>
            <input class="mb-10" type="text" placeholder="Tự động tạo theo tên nếu để trống" name="slug">
        </fieldset>

        <div class="gap22 cols">
            <fieldset class="category">
                <div class="body-title mb-10">Danh mục <span class="tf-color-1">*</span></div>
                <div class="select">
                    <select name="category_id">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </fieldset>
            <fieldset class="brand">
                <div class="body-title mb-10">Thương hiệu <span class="tf-color-1">*</span></div>
                <div class="select">
                    <select name="brand_id">
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </fieldset>
        </div>

        <fieldset class="shortdescription">
            <div class="body-title mb-10">Mô tả ngắn <span class="tf-color-1">*</span></div>
            <textarea class="mb-10 ht-150" name="short_description" placeholder="Nhập tóm tắt đặc điểm nổi bật..." required></textarea>
        </fieldset>

        <fieldset class="description">
            <div class="body-title mb-10">Mô tả chi tiết <span class="tf-color-1">*</span></div>
            <textarea class="mb-10" name="description" placeholder="Nhập thông tin chi tiết về chất liệu, nguồn gốc, kiểu dáng..." required></textarea>
        </fieldset>
    </div>

    <div class="wg-box">
        <fieldset>
            <div class="body-title">Ảnh đại diện sản phẩm</div>
            <div class="upload-image flex-grow">
                <div id="upload-file" class="item up-load">
                    <label class="uploadfile" for="myFile">
                        <span class="icon"><i class="icon-upload-cloud"></i></span>
                        <span class="body-text">Chọn ảnh sản phẩm <span class="tf-color">từ thiết bị</span></span>
                        <input type="file" id="myFile" name="image" accept="image/*">
                    </label>
                </div>
            </div>
        </fieldset>

        <div class="cols gap22">
            <fieldset class="name">
                <div class="body-title mb-10">Giá gốc (đ) <span class="tf-color-1">*</span></div>
                <input class="mb-10" type="number" step="1" placeholder="Nhập giá niêm yết" name="regular_price" required>
            </fieldset>
            <fieldset class="name">
                <div class="body-title mb-10">Giá khuyến mãi (đ)</div>
                <input class="mb-10" type="number" step="1" placeholder="Nhập giá khuyến mãi" name="sale_price">
            </fieldset>
        </div>

        <div class="cols gap22">
            <fieldset class="name">
                <div class="body-title mb-10">Mã SKU <span class="tf-color-1">*</span></div>
                <input class="mb-10" type="text" placeholder="Ví dụ: FS-2026-01" name="SKU" required>
            </fieldset>
            <fieldset class="name">
                <div class="body-title mb-10">Số lượng tồn kho <span class="tf-color-1">*</span></div>
                <input class="mb-10" type="number" placeholder="Số lượng sản phẩm" name="quantity" value="25" required>
            </fieldset>
        </div>

        <div class="cols gap22">
            <fieldset class="name">
                <div class="body-title mb-10">Trạng thái kho hàng</div>
                <div class="select mb-10">
                    <select name="stock_status">
                        <option value="instock">Còn hàng</option>
                        <option value="outofstock">Hết hàng</option>
                    </select>
                </div>
            </fieldset>
            <fieldset class="name">
                <div class="body-title mb-10">Sản phẩm nổi bật?</div>
                <div class="select mb-10">
                    <select name="featured">
                        <option value="0">Không</option>
                        <option value="1">Có (Hiển thị trang chủ)</option>
                    </select>
                </div>
            </fieldset>
        </div>

        <div class="cols gap22">
            <fieldset class="name">
                <div class="body-title mb-10">⚡ Bật Flash Sale?</div>
                <div class="select mb-10">
                    <select name="is_flash_sale">
                        <option value="0">Không</option>
                        <option value="1">Có (Hiển thị khu vực Flash Sale)</option>
                    </select>
                </div>
            </fieldset>
            <fieldset class="name">
                <div class="body-title mb-10">Giá Flash Sale (đ)</div>
                <input class="mb-10" type="number" step="1" placeholder="Giá Flash Sale" name="flash_sale_price">
            </fieldset>
        </div>

        <div class="cols gap10">
            <button class="tf-button w-full" type="submit">Lưu & Đăng Sản Phẩm</button>
        </div>
    </div>
</form>
@endsection
