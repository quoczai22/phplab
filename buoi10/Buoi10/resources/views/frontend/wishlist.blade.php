@extends('layouts.app')

@section('title', 'Sản Phẩm Yêu Thích | Fashion Store')

@section('content')
<div class="content">
    <div class="container" style="margin-top:25px;margin-bottom:40px;">
        <h3 style="font-weight:bold;margin-bottom:20px;color:#111;">❤️ DANH SÁCH SẢN PHẨM YÊU THÍCH</h3>

        @if($wishlists->count() == 0)
            <div class="custom-card text-center" style="padding:50px;">
                <h4>Danh sách yêu thích của bạn đang trống!</h4>
                <p>Hãy nhấn biểu tượng trái tim ♥ trên các sản phẩm bạn yêu thích để dễ dàng xem lại sau.</p>
                <a href="{{ route('collection') }}" class="btn btn-danger btn-lg" style="background:#ff5722;border:none;margin-top:10px;">Khám Phá Sản Phẩm</a>
            </div>
        @else
            <div class="row">
                @foreach($wishlists as $w)
                    @if($w->product)
                    <div class="col-md-3" style="margin-bottom:25px;">
                        <div class="custom-card" style="padding:12px;">
                            <a href="{{ route('product.single', $w->product->slug) }}">
                                <img src="{{ asset($w->product->image) }}" class="img-responsive" style="height:230px;width:100%;object-fit:cover;border-radius:4px;">
                            </a>
                            <h4 style="font-size:15px;margin:12px 0 6px;height:36px;overflow:hidden;">
                                <a href="{{ route('product.single', $w->product->slug) }}" style="color:#111;">{{ $w->product->name }}</a>
                            </h4>
                            <p style="color:#e11d48;font-weight:bold;font-size:16px;">
                                {{ vnd($w->product->effectivePrice()) }}
                            </p>
                            <a href="{{ route('cart.quick.add', $w->product->id) }}" class="btn btn-primary btn-sm" style="background:#ff5722;border:none;">🛒 Thêm Giỏ Hàng</a>
                            <a href="{{ route('wishlist.toggle', $w->product->id) }}" class="btn btn-default btn-sm pull-right" style="color:#e11d48;">Bỏ thích</a>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
