@extends('layouts.app')

@section('title', 'Xu Hướng Thời Trang | Fashion Store')

@section('content')
<div class="content">
    <div class="container"> 			         
        <div class="article-top">
            <h3 style="font-weight:bold;color:#111;">XU HƯỚNG THỜI TRANG 2026</h3>
            <div class="article-bottom">
                <div class="col-md-4 article-para">
                    <a href="{{ route('men') }}">
                        <img src="{{ asset('frontend/images/men.jpg') }}" alt="image" class="img-responsive zoom-img">
                    </a>						
                </div>
                <div class="col-md-8 article-head">
                    <h6><a href="{{ route('men') }}">Phong cách Lịch Lãm Quý Ông Hiện Đại - Bộ Sưu Tập Thu Đông</a></h6>
                    <p>Khám phá cách phối đồ Blazer kết hợp cùng áo sơ mi Oxford và quần Jeans tối màu mang lại vẻ ngoài trẻ trung, sang trọng cho phái mạnh.</p>
                    <p>Các chất liệu vải thân thiện môi trường, co giãn 4 chiều và chống nhăn đang dẫn đầu xu hướng thời trang năm nay.</p>
                </div>
                <div class="clearfix"> </div>
            </div>
            <div class="article-bottom">
                <div class="col-md-8 article-head">
                    <h6><a href="{{ route('women') }}">Bí Quyết Phối Đồ Streetwear & Đầm Lụa Dự Tiệc Cho Phái Đẹp</a></h6>
                    <p>Từ những mẫu áo khoác Denim năng động đến đầm lụa họa tiết mùa hè thanh lịch, bộ sưu tập mới của Fashion Store giúp bạn tỏa sáng trong mọi khoảnh khắc.</p>
                    <p>Đừng quên sử dụng các mã giảm giá SUMMER20 hoặc OFF10 tại giỏ hàng để nhận ưu đãi tốt nhất!</p>
                </div>
                <div class="col-md-4 article-para">
                    <a href="{{ route('women') }}">
                        <img src="{{ asset('frontend/images/women.jpg') }}" alt="image" class="img-responsive zoom-img">
                    </a>						
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>
    </div>
</div>
@endsection
