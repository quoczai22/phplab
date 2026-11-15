@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Sản Phẩm | Admin')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Chỉnh Sửa Sản Phẩm: {{ $product->name }}</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Bảng Điều Khiển</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><a href="{{ route('admin.products') }}"><div class="text-tiny">Sản phẩm</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Chỉnh sửa</div></li>
    </ul>
</div>

<form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data" action="{{ route('admin.products.update', $product->id) }}">
    @csrf
    <div class="wg-box">
        <fieldset class="name">
            <div class="body-title mb-10">Tên sản phẩm <span class="tf-color-1">*</span></div>
            <input class="mb-10" type="text" name="name" value="{{ $product->name }}" required>
        </fieldset>

        <fieldset class="name">
            <div class="body-title mb-10">Đường dẫn tĩnh (Slug) <span class="tf-color-1">*</span></div>
            <input class="mb-10" type="text" name="slug" value="{{ $product->slug }}" required>
        </fieldset>

        <div class="gap22 cols">
            <fieldset class="category">
                <div class="body-title mb-10">Danh mục</div>
                <div class="select">
                    <select name="category_id">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </fieldset>
            <fieldset class="brand">
                <div class="body-title mb-10">Thương hiệu</div>
                <div class="select">
                    <select name="brand_id">
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </fieldset>
        </div>

        <fieldset class="shortdescription">
            <div class="body-title mb-10">Mô tả ngắn</div>
            <textarea class="mb-10 ht-150" name="short_description">{{ $product->short_description }}</textarea>
        </fieldset>

        <fieldset class="description">
            <div class="body-title mb-10">Mô tả chi tiết</div>
            <textarea class="mb-10" name="description">{{ $product->description }}</textarea>
        </fieldset>
    </div>

    <div class="wg-box">
        <fieldset>
            <div class="body-title">Ảnh sản phẩm</div>
            <div class="upload-image flex-grow">
                <div class="item mb-10">
                    <img src="{{ asset($product->image) }}" style="width:100px;height:120px;object-fit:cover;border-radius:4px;">
                </div>
                <div id="upload-file" class="item up-load">
                    <label class="uploadfile" for="myFile">
                        <span class="icon"><i class="icon-upload-cloud"></i></span>
                        <span class="body-text">Chọn ảnh mới để thay đổi</span>
                        <input type="file" id="myFile" name="image" accept="image/*">
                    </label>
                </div>
            </div>
        </fieldset>

        <div class="cols gap22">
            <fieldset class="name">
                <div class="body-title mb-10">Giá gốc (đ) <span class="tf-color-1">*</span></div>
                <input class="mb-10" type="number" step="1" name="regular_price" value="{{ $product->regular_price }}" required>
            </fieldset>
            <fieldset class="name">
                <div class="body-title mb-10">Giá khuyến mãi (đ)</div>
                <input class="mb-10" type="number" step="1" name="sale_price" value="{{ $product->sale_price }}">
            </fieldset>
        </div>

        <div class="cols gap22">
            <fieldset class="name">
                <div class="body-title mb-10">Mã SKU <span class="tf-color-1">*</span></div>
                <input class="mb-10" type="text" name="SKU" value="{{ $product->sku }}" required>
            </fieldset>
            <fieldset class="name">
                <div class="body-title mb-10">Số lượng tồn kho <span class="tf-color-1">*</span></div>
                <input class="mb-10" type="number" name="quantity" value="{{ $product->quantity }}" required>
            </fieldset>
        </div>

        <div class="cols gap22">
            <fieldset class="name">
                <div class="body-title mb-10">Trạng thái kho</div>
                <div class="select mb-10">
                    <select name="stock_status">
                        <option value="instock" {{ $product->stock_status == 'instock' ? 'selected' : '' }}>Còn hàng</option>
                        <option value="outofstock" {{ $product->stock_status == 'outofstock' ? 'selected' : '' }}>Hết hàng</option>
                    </select>
                </div>
            </fieldset>
            <fieldset class="name">
                <div class="body-title mb-10">Sản phẩm nổi bật?</div>
                <div class="select mb-10">
                    <select name="featured">
                        <option value="0" {{ !$product->featured ? 'selected' : '' }}>Không</option>
                        <option value="1" {{ $product->featured ? 'selected' : '' }}>Có</option>
                    </select>
                </div>
            </fieldset>
        </div>

        <div class="cols gap22">
            <fieldset class="name">
                <div class="body-title mb-10">⚡ Bật Flash Sale?</div>
                <div class="select mb-10">
                    <select name="is_flash_sale">
                        <option value="0" {{ !$product->is_flash_sale ? 'selected' : '' }}>Không</option>
                        <option value="1" {{ $product->is_flash_sale ? 'selected' : '' }}>Có</option>
                    </select>
                </div>
            </fieldset>
            <fieldset class="name">
                <div class="body-title mb-10">Giá Flash Sale (đ)</div>
                <input class="mb-10" type="number" step="1" name="flash_sale_price" value="{{ $product->flash_sale_price }}">
            </fieldset>
        </div>

        <div class="cols gap10">
            <button class="tf-button w-full" type="submit">Cập Nhật Thông Tin Sản Phẩm</button>
        </div>
    </div>
</form>
@endsection
