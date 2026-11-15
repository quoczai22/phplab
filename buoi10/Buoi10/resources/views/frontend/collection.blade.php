@extends('layouts.app')

@section('title', 'Bộ Sưu Tập & Danh Mục Sản Phẩm | Fashion Store')

@section('content')
<div class="content">
    <div class="container">
        <div class="product_banner">
            <div class="in-product">
                <div class="wmuSlider example1" style="height: 420px;">
                    <div class="wmuSliderWrapper">
                        @foreach($sliders as $slide)
                        <article style="position: absolute; width: 100%; opacity: 0;"> 
                            <div class="banner-wrap">
                                <div class="banner_left">
                                    <img src="{{ asset($slide->image) }}" class="img-responsive" alt="{{ $slide->title }}">
                                </div>				   		 
                            </div>
                        </article>
                        @endforeach
                    </div>
                    <a class="wmuSliderPrev">Trước</a>
                    <a class="wmuSliderNext">Sau</a>
                </div>
                <script src="{{ asset('frontend/js/jquery.wmuSlider.js') }}"></script> 
                <script>       			
                    $('.example1').wmuSlider({ pagination:false });         
                </script>
            </div>
            <div class="banner_right">
                <h3>Bộ Sưu Tập Thời Trang 2026</h3>
                <p class="nine">149.000 đ</p>
                <p class="sit-in">Bộ sưu tập thời trang cao cấp với đầy đủ kích cỡ (S, M, L, XL) và màu sắc sang trọng. Cam kết chất lượng, bảo hành đổi trả trong 7 ngày và hỗ trợ giao hàng tận nơi trên toàn quốc.</p>
                <a href="{{ route('flash.sale') }}" class="to-cart"><span>Săn Flash Sale</span><img src="{{ asset('frontend/images/plus.png') }}" alt=""></a>
            </div>	
            <div class="clearfix"></div>							
        </div>			 

        <div class="products_top">
            <div class="col-md-9 price-on">
                <!-- Bộ lọc & Công cụ sắp xếp -->
                <div class="mens-toolbar">
                    <div class="sort">
                        <div class="sort-by">
                            <form action="{{ route('collection') }}" method="GET" id="sortForm">
                                @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
                                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                                @if(request('brand')) <input type="hidden" name="brand" value="{{ request('brand') }}"> @endif
                                <label>Sắp xếp theo:</label>
                                <select name="sort" onchange="document.getElementById('sortForm').submit()">
                                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên sản phẩm: A - Z</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                                </select>
                            </form>
                        </div>
                    </div>
                    <div class="pages">   
                        <div class="limiter visible-desktop">
                            <label>Hiển thị {{ $products->count() }} / {{ $products->total() }} sản phẩm</label>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>		
                </div>

                @if(request('q') || request('category') || request('brand') || request('color') || request('size'))
                    <div class="alert alert-info" style="padding:10px 15px;border-radius:6px;">
                        Bộ lọc đang kích hoạt:
                        @if(request('q')) <strong>Từ khóa: "{{ request('q') }}"</strong> @endif
                        @if(request('category')) | <strong>Danh mục: {{ request('category') }}</strong> @endif
                        @if(request('brand')) | <strong>Thương hiệu: {{ request('brand') }}</strong> @endif
                        @if(request('size')) | <strong>Size: {{ request('size') }}</strong> @endif
                        @if(request('color')) | <strong>Màu: {{ request('color') }}</strong> @endif
                        <a href="{{ route('collection') }}" class="pull-right" style="color:#d9534f;font-weight:bold;">[Xóa bộ lọc]</a>
                    </div>
                @endif

                <div class="product_box1">   
                    @forelse($products as $idx => $product)
                    <div class="col-md-4 grid-4 {{ ($idx + 1) % 3 == 0 ? 'grid-in' : '' }}" style="margin-bottom:25px;position:relative;">
                        @if($product->is_flash_sale)
                            <span class="flash-badge">⚡ Flash Sale</span>
                        @endif
                        <a href="{{ route('product.single', $product->slug) }}">
                            <img src="{{ asset($product->image) }}" class="img-responsive" alt="{{ $product->name }}" style="height:260px;width:100%;object-fit:cover;">
                        </a>
                        <div class="tab_desc1">
                            <h3><a href="{{ route('product.single', $product->slug) }}">{{ $product->name }}</a></h3>
                            <p>
                                {{ vnd($product->effectivePrice()) }}
                                @if($product->effectivePrice() < $product->regular_price)
                                    <del style="color:#999;font-size:12px;">{{ vnd($product->regular_price) }}</del>
                                @endif
                            </p>
                            <a href="{{ route('cart.quick.add', $product->id) }}" class="to-cart"><span>Thêm Giỏ Hàng</span><img src="{{ asset('frontend/images/plus.png') }}" alt=""></a>
                            <a href="{{ route('wishlist.toggle', $product->id) }}" class="wishlist-btn" title="Yêu thích">♥</a>
                        </div>
                    </div>
                    @if(($idx + 1) % 3 == 0)
                        <div class="clearfix"></div>
                    @endif
                    @empty
                        <div class="col-md-12">
                            <div class="alert alert-warning" style="padding:20px;text-align:center;">
                                Không tìm thấy sản phẩm nào phù hợp với bộ lọc đã chọn.
                            </div>
                        </div>
                    @endforelse
                    <div class="clearfix"></div>
                </div>

                <div class="pages">   
                    {{ $products->links() }}
                    <div class="clearfix"></div>
                </div>
            </div>

            <!-- THANH ĐIỀU HƯỚNG BÊN PHẢI -->
            <div class="col-md-3 product_right">
                <div class="custom-card">
                    <h4 style="margin-top:0;font-weight:bold;border-bottom:2px solid #111;padding-bottom:8px;">DANH MỤC THỜI TRANG</h4>
                    <ul class="list-unstyled" style="line-height:2.2;">
                        <li><a href="{{ route('collection') }}" style="color:#111;font-weight:bold;">• Tất cả sản phẩm</a></li>
                        @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('collection', ['category' => $cat->slug]) }}" style="color:{{ request('category') == $cat->slug ? '#ff5722' : '#444' }};">
                                    • {{ $cat->name }} <span class="badge pull-right">{{ $cat->products_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="custom-card">
                    <h4 style="margin-top:0;font-weight:bold;border-bottom:2px solid #111;padding-bottom:8px;">THƯƠNG HIỆU NỔI BẬT</h4>
                    <ul class="list-unstyled" style="line-height:2.2;">
                        @foreach($brands as $b)
                            <li>
                                <a href="{{ route('collection', ['brand' => $b->slug]) }}" style="color:{{ request('brand') == $b->slug ? '#ff5722' : '#444' }};">
                                    • {{ $b->name }} <span class="badge pull-right">{{ $b->products_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="custom-card">
                    <h4 style="margin-top:0;font-weight:bold;border-bottom:2px solid #111;padding-bottom:8px;">LỌC THEO SIZE & MÀU SẮC</h4>
                    <div style="margin-bottom:12px;">
                        <strong>Kích cỡ (Size):</strong><br>
                        @foreach(['S', 'M', 'L', 'XL'] as $sz)
                            <a href="{{ route('collection', array_merge(request()->all(), ['size' => $sz])) }}" class="btn btn-xs {{ request('size') == $sz ? 'btn-danger' : 'btn-default' }}" style="margin-top:5px;">{{ $sz }}</a>
                        @endforeach
                    </div>
                    <div>
                        <strong>Màu sắc:</strong><br>
                        @foreach(['Đen', 'Trắng', 'Xanh Navy', 'Đỏ Đô'] as $cl)
                            <a href="{{ route('collection', array_merge(request()->all(), ['color' => $cl])) }}" class="btn btn-xs {{ request('color') == $cl ? 'btn-danger' : 'btn-default' }}" style="margin-top:5px;">{{ $cl }}</a>
                        @endforeach
                    </div>
                </div>

                <h3 class="m_1">Đang Giảm Giá</h3>
                <div class="sale_grid">
                    @foreach($onSaleProducts as $sp)
                    <ul class="grid_1">
                        <li class="grid_1-img">
                            <a href="{{ route('product.single', $sp->slug) }}">
                                <img src="{{ asset($sp->image) }}" class="img-responsive" alt="{{ $sp->name }}">
                            </a>
                        </li>
                        <li class="grid_1-desc">
                            <h4><a href="{{ route('product.single', $sp->slug) }}">{{ Str::limit($sp->name, 22) }}</a></h4>
                            <p>{{ vnd($sp->effectivePrice()) }}</p>
                        </li>
                        <div class="clearfix"> </div>
                    </ul>
                    @endforeach
                </div>

                <h3 class="m_1">Sản Phẩm Bán Chạy</h3>
                <div class="sale_grid">
                    @foreach($featuredProducts as $fp)
                    <ul class="grid_1">
                        <li class="grid_1-img">
                            <a href="{{ route('product.single', $fp->slug) }}">
                                <img src="{{ asset($fp->image) }}" class="img-responsive" alt="{{ $fp->name }}">
                            </a>
                        </li>
                        <li class="grid_1-desc">
                            <h4><a href="{{ route('product.single', $fp->slug) }}">{{ Str::limit($fp->name, 22) }}</a></h4>
                            <p>{{ vnd($fp->effectivePrice()) }}</p>
                        </li>
                        <div class="clearfix"> </div>
                    </ul>
                    @endforeach
                </div>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
</div>
@endsection
