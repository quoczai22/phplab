@extends('layouts.app')

@section('title', 'Fashion Store | Thời Trang Cao Cấp')

@section('content')
<div class="content">
    <div class="container">
        <div class="women-in">
            <div class="col-md-9 col-d">
                <div class="banner">
                    <div class="banner-matter">
                        <label>Bộ Sưu Tập Mới</label>
                        <h2>Mùa Hè 2026</h2>
                        <p>Tỏa sáng phong cách và sự tự tin cùng Fashion Store</p>
                    </div>
                    <div class="you">
                        <span>40<label>%</label></span>
                        <small>Giảm</small>
                    </div>			
                    <p class="para-in">Bộ sưu tập thời trang độc quyền - Nhập mã <strong>OFF10</strong> để giảm thêm 10% tại giỏ hàng!</p>
                </div>

                <!-- FLASH SALE -->
                @if($flashSaleProducts->count() > 0)
                <div class="in-line" style="background:#fff1f2;border:2px solid #fda4af;padding:18px;border-radius:8px;margin-top:20px;">
                    <div class="para-an" style="border-bottom:1px solid #fecdd3;padding-bottom:10px;margin-bottom:15px;">
                        <h3 style="color:#e11d48;margin:0;">⚡ GIỜ VÀNG FLASH SALE <span id="flash-timer" style="background:#e11d48;color:#fff;padding:3px 10px;border-radius:4px;font-size:15px;margin-left:10px;">04:59:50</span></h3>
                        <p style="color:#9f1239;margin-top:5px;">Sản phẩm ưu đãi số lượng giới hạn - Nhanh tay kẻo lỡ!</p>
                    </div>
                    <div class="row">
                        @foreach($flashSaleProducts->take(3) as $fp)
                        <div class="col-md-4 you-para" style="position:relative;">
                            <span class="flash-badge">-{{ $fp->discountPercent() }}%</span>
                            <a href="{{ route('product.single', $fp->slug) }}">
                                <img class="img-responsive pic-in" src="{{ asset($fp->image) }}" alt="{{ $fp->name }}">
                            </a>
                            <p><a href="{{ route('product.single', $fp->slug) }}" style="color:#111;font-weight:bold;">{{ $fp->name }}</a></p>
                            <span>
                                <strong style="color:#e11d48;font-size:16px;">{{ vnd($fp->effectivePrice()) }}</strong>
                                <del style="color:#888;font-size:12px;">{{ vnd($fp->regular_price) }}</del>
                                | <a href="{{ route('cart.quick.add', $fp->id) }}" style="color:#e11d48;font-weight:bold;">Mua ngay</a>
                            </span>
                        </div>
                        @endforeach
                        <div class="clearfix"></div>
                    </div>
                </div>
                @endif

                <!-- SẢN PHẨM MỚI NHẤT -->
                <div class="in-line">
                    <div class="para-an">
                        <h3>SẢN PHẨM MỚI NHẤT</h3>
                        <p>Các thiết kế xu hướng vừa cập bến trong tuần này.</p>
                    </div>
                    <div class="lady-in">
                        @foreach($latestProducts as $idx => $product)
                        <div class="col-md-4 you-para {{ ($idx + 1) % 3 == 0 ? 'para-grid' : '' }}" style="margin-bottom:25px;">
                            <a href="{{ route('product.single', $product->slug) }}">
                                <img class="img-responsive pic-in" src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                            </a>
                            @if($product->discountPercent() > 0)
                            <div class="you-in">
                                <span>{{ $product->discountPercent() }}<label>%</label></span>
                                <small>Giảm</small>
                            </div>
                            @endif
                            <p><a href="{{ route('product.single', $product->slug) }}" style="color:#333;">{{ $product->name }}</a></p>
                            <span>
                                {{ vnd($product->effectivePrice()) }}
                                @if($product->effectivePrice() < $product->regular_price)
                                    <del style="color:#999;font-size:12px;">{{ vnd($product->regular_price) }}</del>
                                @endif
                                | <label class="cat-in"> </label>
                                <a href="{{ route('cart.quick.add', $product->id) }}">Thêm vào giỏ</a>
                                <a href="{{ route('wishlist.toggle', $product->id) }}" class="wishlist-btn" title="Thêm vào yêu thích">♥</a>
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

            <div class="col-md-3 col-m-left">
                <div class="in-left">				
                    <p class="code">BỘ SƯU TẬP ĐẶC SẮC</p>		
                    <div class="cool"></div>		
                </div>

                <!-- MÃ GIẢM GIÁ -->
                <div class="custom-card" style="margin-top:15px;background:#fffbeb;border-color:#fde68a;">
                    <h4 style="margin-top:0;color:#b45309;font-size:15px;font-weight:bold;">🎟️ MÃ GIẢM GIÁ HÔM NAY</h4>
                    @foreach($coupons as $cp)
                    <div style="border:1px dashed #f59e0b;padding:8px;margin-bottom:8px;border-radius:4px;background:#fff;">
                        <strong style="color:#d97706;font-size:14px;">{{ $cp->code }}</strong>
                        <span class="label label-warning pull-right">
                            Giảm {{ $cp->type == 'percent' ? $cp->value . '%' : vnd($cp->value) }}
                        </span>
                        <div style="font-size:11px;color:#666;margin-top:4px;">
                            Đơn từ: {{ vnd($cp->cart_value) }} | HSD: {{ date('d/m/Y', strtotime($cp->expiry_date)) }}
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="discount">
                    <a href="{{ route('women') }}"><img class="img-responsive pic-in" src="{{ asset('frontend/images/p2.jpg') }}" alt="Thời trang nữ"></a>		
                    <p class="no-more">Ưu đãi độc quyền <span>Thời trang nữ</span></p>					
                    <a href="{{ route('women') }}" class="know-more">Xem ngay</a>
                </div>
                <div class="discount">
                    <a href="{{ route('collection') }}"><img class="img-responsive pic-in" src="{{ asset('frontend/images/p3.jpg') }}" alt="Thời trang đầm"></a>			
                    <p class="no-more no-get">Giảm giá hấp dẫn <span>Đầm dạ tiệc</span></p>					
                    <a href="{{ route('collection') }}" class="know-more">Xem ngay</a>
                </div>
                <div class="twitter-in">
                    <h5>PHẢN HỒI KHÁCH HÀNG</h5>
                    <span class="twitter-ic"></span>
                    <div class="up-date">
                        <p>@thanhhang: Áo khoác Denim form rất chuẩn, đóng gói cẩn thận và giao nhanh!</p>
                        <a href="{{ route('collection') }}">Xem chi tiết sản phẩm</a>
                        <p class="ago">Đánh giá 5 sao từ khách đã mua</p>
                    </div>
                    <a href="{{ route('flash.sale') }}" class="tweets">Khám phá Flash Sale</a>
                    <div class="clearfix"> </div>
                </div>
            </div>
            <div class="clearfix"> </div>
        </div>

        <!-- SẢN PHẨM NỔI BẬT KHÁC -->
        <div class="lady-in-on">
            <div class="buy-an">
                <h3>SẢN PHẨM NỔI BẬT</h3>
                <p>Những thiết kế được yêu thích nhất trong tuần.</p>
            </div>
            <ul id="flexiselDemo1">			
                @foreach($featuredProducts as $fp)
                <li>
                    <a href="{{ route('product.single', $fp->slug) }}">
                        <img class="img-responsive women" src="{{ asset($fp->image) }}" alt="{{ $fp->name }}" style="height:260px;object-fit:cover;width:100%;">
                    </a>
                    <div class="hide-in">
                        <p>{{ $fp->name }}</p>
                        <span>{{ vnd($fp->effectivePrice()) }} | <a href="{{ route('cart.quick.add', $fp->id) }}">Mua ngay</a></span>
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

        <!-- CHỨC NĂNG NHÓM C: GỢI Ý CÁ NHÂN HÓA (PERSONALIZED RECOMMENDATIONS) -->
        <div class="custom-card" style="margin:30px 0;background:#f8fafc;padding:25px;border-radius:10px;">
            <div class="clearfix" style="margin-bottom:15px;">
                <h3 style="margin:0;font-weight:bold;color:#0f172a;" class="pull-left">
                    ✨ GỢI Ý DÀNH RIÊNG CHO BẠN
                </h3>
                <span class="pull-right text-muted" style="font-size:13px;padding-top:6px;">
                    Dựa trên sở thích & lịch sử xem sản phẩm của bạn
                </span>
            </div>
            <div class="row">
                @foreach($personalizedProducts as $rec)
                <div class="col-md-3 col-sm-6" style="margin-bottom:15px;">
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:12px;text-align:center;box-shadow:0 2px 4px rgba(0,0,0,0.02);">
                        <a href="{{ route('product.single', $rec->slug) }}">
                            <img src="{{ asset($rec->image) }}" class="img-responsive" style="height:210px;width:100%;object-fit:cover;border-radius:6px;margin-bottom:10px;">
                            <h4 style="font-size:14px;font-weight:bold;margin:5px 0;height:38px;overflow:hidden;color:#111;">{{ $rec->name }}</h4>
                        </a>
                        <div style="margin:8px 0;">
                            <strong style="color:#e11d48;font-size:16px;">{{ vnd($rec->effectivePrice()) }}</strong>
                            @if($rec->effectivePrice() < $rec->regular_price)
                                <del style="color:#94a3b8;font-size:12px;margin-left:5px;">{{ vnd($rec->regular_price) }}</del>
                            @endif
                        </div>
                        <a href="{{ route('cart.quick.add', $rec->id) }}" class="btn btn-sm btn-block" style="background:#ff5722;color:#fff;font-weight:bold;">
                            🛒 Thêm Giỏ Hàng
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        var duration = 4 * 3600 + 58 * 60 + 19;
        var now = Math.floor(Date.now() / 1000);
        var targetTime = localStorage.getItem('flash_sale_end_time');

        if (!targetTime || parseInt(targetTime) <= now) {
            targetTime = now + duration;
            localStorage.setItem('flash_sale_end_time', targetTime);
        } else {
            targetTime = parseInt(targetTime);
        }

        function updateCountdown() {
            var current = Math.floor(Date.now() / 1000);
            var remain = Math.max(0, targetTime - current);

            var h = Math.floor(remain / 3600);
            var m = Math.floor((remain % 3600) / 60);
            var s = remain % 60;

            var el = document.getElementById('flash-timer');
            if (el) {
                el.innerText = (h < 10 ? '0'+h : h) + ':' + (m < 10 ? '0'+m : m) + ':' + (s < 10 ? '0'+s : s);
            }
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    })();
</script>
@endsection
