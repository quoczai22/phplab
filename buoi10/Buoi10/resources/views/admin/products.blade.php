@extends('layouts.admin')

@section('title', 'Quản Lý Sản Phẩm | Admin')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Danh Sách Sản Phẩm ({{ $products->total() }})</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Bảng Điều Khiển</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Tất cả sản phẩm</div></li>
    </ul>
</div>

<div class="wg-box">
    <div class="flex items-center justify-between gap10 flex-wrap">
        <div class="wg-filter flex-grow">
            <form class="form-search" action="{{ route('admin.products') }}" method="GET">
                <fieldset class="name">
                    <input type="text" placeholder="Tìm kiếm theo tên sản phẩm hoặc mã SKU..." name="name" value="{{ request('name') }}">
                </fieldset>
                <div class="button-submit">
                    <button type="submit"><i class="icon-search"></i></button>
                </div>
            </form>
        </div>
        <a class="tf-button style-1 w208" href="{{ route('admin.products.add') }}"><i class="icon-plus"></i>Thêm sản phẩm mới</a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá gốc</th>
                    <th>Giá bán</th>
                    <th>Flash Sale</th>
                    <th>Mã SKU</th>
                    <th>Danh mục</th>
                    <th>Thương hiệu</th>
                    <th>Trạng thái</th>
                    <th>Tồn kho</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td class="pname">
                        <div class="image">
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="image">
                        </div>
                        <div class="name">
                            <a href="{{ route('product.single', $product->slug) }}" target="_blank" class="body-title-2">{{ $product->name }}</a>
                            <div class="text-tiny mt-3">{{ $product->variants->count() }} biến thể</div>
                        </div>
                    </td>
                    <td>{{ vnd($product->regular_price) }}</td>
                    <td><strong style="color:#e11d48;">{{ vnd($product->sale_price ?? $product->regular_price) }}</strong></td>
                    <td>
                        @if($product->is_flash_sale)
                            <span class="badge bg-danger">Flash Sale</span>
                        @else
                            <span class="badge bg-secondary">Thường</span>
                        @endif
                    </td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td>{{ $product->brand->name ?? '-' }}</td>
                    <td>
                        @if($product->quantity > 0)
                            <span class="badge bg-success">Còn hàng</span>
                        @else
                            <span class="badge bg-danger">Hết hàng</span>
                        @endif
                    </td>
                    <td>{{ $product->quantity }}</td>
                    <td>
                        <div class="list-icon-function">
                            <a href="{{ route('product.single', $product->slug) }}" target="_blank">
                                <div class="item eye">
                                    <i class="icon-eye"></i>
                                </div>
                            </a>
                            <a href="{{ route('admin.products.edit', $product->id) }}">
                                <div class="item edit">
                                    <i class="icon-edit-3"></i>
                                </div>
                            </a>
                            <form action="{{ route('admin.products.delete', $product->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="item text-danger delete" style="border:none;background:none;" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
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

    <div class="divider"></div>
    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
        {{ $products->links() }}
    </div>
</div>
@endsection
