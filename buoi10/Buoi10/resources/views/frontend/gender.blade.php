@extends('layouts.app')

@section('title', $pageTitle . ' | Fashion Store')

@section('content')
<div class="content">
    <div class="container">
        <div class="col-m-on">
            <div class="in-line">
                <div class="para-all">
                    <h3>{{ $pageTitle }}</h3>
                    <p>Khám phá các thiết kế thời trang hiện đại dẫn đầu xu hướng 2026.</p>
                </div>
                <div class="lady-on">
                    @foreach($products as $idx => $product)
                    <div class="col-md-4 you-men {{ ($idx + 1) % 3 == 0 ? 'para-grid' : '' }}" style="margin-bottom:25px;">
                        <a href="{{ route('product.single', $product->slug) }}">
                            <img class="img-responsive pic-in" src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="height:300px;width:100%;object-fit:cover;">
                        </a>
                        @if($product->discountPercent() > 0)
                        <div class="you-onto">
                            <span>-{{ $product->discountPercent() }}<label>%</label></span>
                            <small>GIẢM</small>
                        </div>
                        @endif
                        <p><a href="{{ route('product.single', $product->slug) }}" style="color:#222;">{{ $product->name }}</a></p>
                        <span>
                            {{ vnd($product->effectivePrice()) }}
                            | <label class="cat-in"> </label>
                            <a href="{{ route('cart.quick.add', $product->id) }}">Thêm Giỏ Hàng</a>
                            <a href="{{ route('wishlist.toggle', $product->id) }}" class="wishlist-btn">♥</a>
                        </span>
                    </div>
                    @if(($idx + 1) % 3 == 0)
                        <div class="clearfix"></div>
                    @endif
                    @endforeach
                    <div class="clearfix"> </div>
                </div>
            </div>
        </div>

        <div class="lady-in-on">
            <div class="buy-an">
                <h3>SẢN PHẨM KHÁC DÀNH CHO BẠN</h3>
                <p>Xem thêm các mẫu trang phục nổi bật khác tại cửa hàng.</p>
            </div>
            <ul id="flexiselDemo1">			
                @foreach($otherProducts as $op)
                <li>
                    <a href="{{ route('product.single', $op->slug) }}">
                        <img class="img-responsive women" src="{{ asset($op->image) }}" alt="{{ $op->name }}" style="height:250px;width:100%;object-fit:cover;">
                    </a>
                    <div class="hide-in">
                        <p>{{ $op->name }}</p>
                        <span>{{ vnd($op->effectivePrice()) }} | <a href="{{ route('cart.quick.add', $op->id) }}">Mua Ngay</a></span>
                    </div>
                </li>
                @endforeach
            </ul>
            <script type="text/javascript">
                $(window).load(function() {
                    $("#flexiselDemo1").flexisel({
                        visibleItems: 4,
                        animationSpeed: 1000,
                        autoPlay: true,
                        autoPlaySpeed: 3000,    		
                        pauseOnHover: true,
                        enableResponsiveBreakpoints: true,
                        responsiveBreakpoints: { 
                            portrait: { changePoint:480, visibleItems: 1 }, 
                            landscape: { changePoint:640, visibleItems: 2 },
                            tablet: { changePoint:768, visibleItems: 3 }
                        }
                    });
                });
            </script>
            <script type="text/javascript" src="{{ asset('frontend/js/jquery.flexisel.js') }}"></script>
        </div>
    </div>
</div>
@endsection
