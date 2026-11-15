@extends('layouts.app')

@section('title', $product->name . ' | Chi Tiết Sản Phẩm')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/css/etalage.css') }}">
@endpush

@section('content')
<div class="content">
    <div class="container">
        <div class="single">
            <div class="col-md-9">
                <div class="single_grid">
                    <div class="grid images_3_of_2">
                        @php
                            $gallery = array_filter(explode(',', $product->images ?: $product->image));
                            if (empty($gallery)) $gallery = [$product->image];
                        @endphp
                        <ul id="etalage">
                            <li>
                                <img class="etalage_thumb_image img-responsive" src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                                <img class="etalage_source_image img-responsive" src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                            </li>
                            @foreach($gallery as $gImg)
                            <li>
                                <img class="etalage_thumb_image img-responsive" src="{{ asset(trim($gImg)) }}" alt="">
                                <img class="etalage_source_image img-responsive" src="{{ asset(trim($gImg)) }}" alt="">
                            </li>
                            @endforeach
                        </ul>
                        <div class="clearfix"> </div>		
                    </div> 

                    <div class="span1_of_1_des">
                        <div class="desc1">
                            <h3>{{ $product->name }}</h3>
                            <p style="color:#666;">
                                Mã SKU: <strong>{{ $product->sku }}</strong> |
                                Danh mục: <strong>{{ $product->category->name ?? 'Thời trang' }}</strong> |
                                Thương hiệu: <strong>{{ $product->brand->name ?? 'Fashion Store' }}</strong>
                            </p>
                            <p>{{ $product->short_description }}</p>

                            <h5>
                                {{ vnd($product->effectivePrice()) }}
                                @if($product->effectivePrice() < $product->regular_price)
                                    <del style="color:#999;font-size:16px;margin-left:8px;">{{ vnd($product->regular_price) }}</del>
                                @endif
                                @if($product->is_flash_sale)
                                    <a href="{{ route('flash.sale') }}" style="color:#e11d48;font-weight:bold;margin-left:10px;">⚡ Đang Giảm Giá Giờ Vàng (-{{ $product->discountPercent() }}%)</a>
                                @endif
                            </h5>

                            <!-- Tình trạng kho -->
                            <div style="margin:10px 0;">
                                Tình trạng:
                                @if($product->quantity > 0 && $product->stock_status == 'instock')
                                    <span class="label label-success">Còn hàng trong kho ({{ $product->quantity }} sản phẩm)</span>
                                @else
                                    <span class="label label-danger">Tạm thời hết hàng</span>
                                @endif
                            </div>

                            <!-- Form mua hàng & chọn biến thể -->
                            <form action="{{ route('cart.add') }}" method="POST" class="available">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <h4>Tùy chọn kích cỡ & màu sắc:</h4>

                                @if($product->variants->count() > 0)
                                    <div style="margin-bottom:12px;">
                                        <label style="font-weight:600;display:block;margin-bottom:5px;">Chọn phiên bản (Size - Màu - Tồn kho):</label>
                                        <select name="variant_id" class="form-control" style="max-width:340px;">
                                            @foreach($product->variants as $v)
                                                <option value="{{ $v->id }}">
                                                    Size {{ $v->size }} - Màu {{ $v->color }} ({{ vnd($v->price ?: $product->effectivePrice()) }} | Còn {{ $v->quantity }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <ul>
                                    <li>Kích cỡ:
                                        <select name="size">
                                            <option value="S">S</option>
                                            <option value="M" selected>M</option>
                                            <option value="L">L</option>
                                            <option value="XL">XL</option>
                                        </select>
                                    </li>
                                    <li>Màu:
                                        <select name="color">
                                            <option value="Đen">Đen</option>
                                            <option value="Trắng">Trắng</option>
                                            <option value="Xanh Navy">Xanh Navy</option>
                                            <option value="Đỏ Đô">Đỏ Đô</option>
                                        </select>
                                    </li>
                                    <li>Số lượng:
                                        <select name="quantity">
                                            @for($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </li>
                                </ul>

                                <div class="form-in" style="margin-top:15px;">
                                    <button type="submit" style="background:#ff5722;color:#fff;border:none;padding:12px 24px;font-size:15px;font-weight:bold;cursor:pointer;">
                                        🛒 THÊM VÀO GIỎ HÀNG
                                    </button>
                                    <button type="submit" name="buy_now" value="1" style="background:#111;color:#fff;border:none;padding:12px 24px;font-size:15px;font-weight:bold;cursor:pointer;margin-left:8px;">
                                        ⚡ MUA NGAY
                                    </button>
                                </div>
                                <span class="span_right" style="margin-top:14px;display:inline-block;">
                                    <a href="{{ route('wishlist.toggle', $product->id) }}" style="color:#e11d48;font-weight:bold;">
                                        ♥ Thêm vào danh sách yêu thích
                                    </a>
                                </span>
                                <div class="clearfix"></div>
                            </form>

                            <div class="share-desc">
                                <div class="share">
                                    <h4>Chia sẻ sản phẩm:</h4>
                                    <ul class="share_nav">
                                        <li><a href="#"><img src="{{ asset('frontend/images/facebook.png') }}" title="Facebook"></a></li>
                                        <li><a href="#"><img src="{{ asset('frontend/images/twitter.png') }}" title="Twitter"></a></li>
                                        <li><a href="#"><img src="{{ asset('frontend/images/rss.png') }}" title="Rss"></a></li>
                                        <li><a href="#"><img src="{{ asset('frontend/images/gpluse.png') }}" title="Google+"></a></li>
                                    </ul>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <!-- Tabs thông tin sản phẩm & đánh giá -->
                <div class="sap_tabs">	
                    <div id="horizontalTab" style="display: block; width: 100%; margin: 0px;">
                        <ul class="resp-tabs-list">
                            <li class="resp-tab-item" aria-controls="tab_item-0" role="tab"><span>Mô tả sản phẩm</span></li>
                            <li class="resp-tab-item" aria-controls="tab_item-1" role="tab"><span>Bảng Size & Tồn Kho</span></li>
                            <li class="resp-tab-item" aria-controls="tab_item-2" role="tab"><span>Đánh giá từ khách hàng ({{ $product->reviews->count() }})</span></li>
                            <div class="clearfix"></div>
                        </ul>				  	 
                        <div class="resp-tabs-container">
                            <h2 class="resp-accordion resp-tab-active" role="tab" aria-controls="tab_item-0"><span class="resp-arrow"></span>Mô tả sản phẩm</h2>
                            <div class="tab-1 resp-tab-content resp-tab-content-active" aria-labelledby="tab_item-0" style="display:block">
                                <div class="facts">
                                    <p>{{ $product->description }}</p>
                                    <ul>
                                        <li>Chất liệu vải cao cấp, co giãn thoáng mát, thân thiện với làn da</li>
                                        <li>Đường may tỉ mỉ, tôn dáng và chuẩn form người mặc Việt Nam</li>
                                        <li>Cam kết sản phẩm thực tế đúng 100% so với hình ảnh mô tả</li>
                                        <li>Hỗ trợ đổi trả thuận tiện trong vòng 7 ngày nếu không vừa Size</li>
                                    </ul>         
                                </div>
                            </div>

                            <h2 class="resp-accordion" role="tab" aria-controls="tab_item-1"><span class="resp-arrow"></span>Bảng Size & Tồn Kho</h2>
                            <div class="tab-1 resp-tab-content" aria-labelledby="tab_item-1">
                                <div class="facts">									
                                    <h4>Số lượng tồn kho theo từng kích cỡ & màu sắc:</h4>
                                    <table class="table table-bordered" style="background:#fff;margin-top:10px;">
                                        <thead>
                                            <tr>
                                                <th>Mã SKU</th>
                                                <th>Kích cỡ</th>
                                                <th>Màu sắc</th>
                                                <th>Đơn giá</th>
                                                <th>Số lượng còn</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($product->variants as $v)
                                            <tr>
                                                <td>{{ $v->sku }}</td>
                                                <td><span class="label label-primary">{{ $v->size }}</span></td>
                                                <td>{{ $v->color }}</td>
                                                <td>{{ vnd($v->price ?: $product->effectivePrice()) }}</td>
                                                <td>{{ $v->quantity }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>	
                            </div>									

                            <h2 class="resp-accordion" role="tab" aria-controls="tab_item-2"><span class="resp-arrow"></span>Đánh giá</h2>
                            <div class="tab-1 resp-tab-content" aria-labelledby="tab_item-2">
                                <div class="facts">
                                    <h4>Nhận xét từ khách hàng đã mua ({{ $product->reviews->count() }})</h4>
                                    @forelse($product->reviews as $rev)
                                        <div style="border-bottom:1px solid #eee;padding:12px 0;">
                                            <strong>👤 {{ $rev->user->name ?? 'Khách hàng' }}</strong>
                                            <span style="color:#f59e0b;margin-left:10px;">
                                                @for($i=1; $i<=5; $i++)
                                                    {{ $i <= $rev->rating ? '★' : '☆' }}
                                                @endfor
                                            </span>
                                            <small class="text-muted pull-right">{{ $rev->created_at->format('d/m/Y H:i') }}</small>
                                            <p style="margin:6px 0 0;">{{ $rev->comment }}</p>
                                        </div>
                                    @empty
                                        <p>Chưa có đánh giá nào cho sản phẩm này. Hãy chia sẻ trải nghiệm đầu tiên của bạn!</p>
                                    @endforelse

                                    <hr>
                                    <h4>Gửi đánh giá của bạn</h4>
                                    <form action="{{ route('product.review', $product->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label>Mức độ hài lòng:</label>
                                            <select name="rating" class="form-control" style="max-width:240px;">
                                                <option value="5">★★★★★ (5 sao - Cực kỳ hài lòng)</option>
                                                <option value="4">★★★★☆ (4 sao - Rất tốt)</option>
                                                <option value="3">★★★☆☆ (3 sao - Bình thường)</option>
                                                <option value="2">★★☆☆☆ (2 sao - Tạm được)</option>
                                                <option value="1">★☆☆☆☆ (1 sao - Chưa hài lòng)</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Nội dung nhận xét:</label>
                                            <textarea name="comment" rows="3" class="form-control" placeholder="Chia sẻ cảm nhận chi tiết của bạn về chất liệu, form dáng sản phẩm..." required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary" style="background:#ff5722;border:none;font-weight:bold;">Gửi Đánh Giá</button>
                                    </form>
                                </div>	
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CHỨC NĂNG NHÓM C: RECOMMENDATION SYSTEM -->
                @if($boughtTogether->isNotEmpty())
                <div class="custom-card" style="margin-top:25px;border-top:3px solid #ff5722;">
                    <h4 style="margin-top:0;font-weight:bold;color:#111;">🛍️ Khách Hàng Thường Mua Cùng Nhau</h4>
                    <p style="color:#666;font-size:13px;margin-bottom:15px;">Dựa trên phân tích thói quen mua sắm cùng sản phẩm này:</p>
                    <div class="row">
                        @foreach($boughtTogether as $bt)
                        <div class="col-xs-6 col-md-3">
                            <div style="border:1px solid #eee;border-radius:6px;padding:8px;text-align:center;margin-bottom:10px;">
                                <a href="{{ route('product.single', $bt->slug) }}">
                                    <img src="{{ asset($bt->image) }}" class="img-responsive" style="height:140px;width:100%;object-fit:cover;border-radius:4px;margin-bottom:6px;">
                                    <div style="font-weight:bold;font-size:13px;height:36px;overflow:hidden;color:#222;">{{ $bt->name }}</div>
                                </a>
                                <div style="color:#e11d48;font-weight:bold;margin:6px 0;">{{ vnd($bt->effectivePrice()) }}</div>
                                <a href="{{ route('cart.quick.add', $bt->id) }}" class="btn btn-xs btn-danger" style="background:#ff5722;border:none;">+ Thêm Giỏ</a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($personalizedProducts->isNotEmpty())
                <div class="custom-card" style="margin-top:20px;background:#f8fafc;">
                    <h4 style="margin-top:0;font-weight:bold;color:#0f172a;">✨ Gợi Ý Dành Riêng Cho Bạn (Cá Nhân Hóa)</h4>
                    <div class="row">
                        @foreach($personalizedProducts as $rec)
                        <div class="col-xs-6 col-md-3">
                            <div style="border:1px solid #e2e8f0;background:#fff;border-radius:6px;padding:8px;text-align:center;margin-bottom:10px;">
                                <a href="{{ route('product.single', $rec->slug) }}">
                                    <img src="{{ asset($rec->image) }}" class="img-responsive" style="height:140px;width:100%;object-fit:cover;border-radius:4px;margin-bottom:6px;">
                                    <div style="font-weight:bold;font-size:13px;height:36px;overflow:hidden;color:#222;">{{ $rec->name }}</div>
                                </a>
                                <div style="color:#e11d48;font-weight:bold;margin:6px 0;">{{ vnd($rec->effectivePrice()) }}</div>
                                <a href="{{ route('product.single', $rec->slug) }}" class="btn btn-xs btn-primary" style="background:#111;border:none;">Xem Ngay</a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- CỘT PHẢI: DANH MỤC & SẢN PHẨM LIÊN QUAN -->
            <div class="col-md-3">
                <div class="w_sidebar">
                    <section class="sky-form">
                        <h4>Danh mục sản phẩm</h4>
                        <div class="row1 scroll-pane">
                            <div class="col col-4">
                                @foreach($categories as $cat)
                                    <label class="checkbox">
                                        <a href="{{ route('collection', ['category' => $cat->slug]) }}" style="color:#555;">
                                            • {{ $cat->name }} ({{ $cat->products_count }})
                                        </a>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <section class="sky-form">
                        <h4>Sản phẩm liên quan</h4>
                        <div style="padding:15px;">
                            @foreach($relatedProducts as $rel)
                                <div style="margin-bottom:15px;border-bottom:1px dashed #eee;padding-bottom:10px;">
                                    <a href="{{ route('product.single', $rel->slug) }}">
                                        <img src="{{ asset($rel->image) }}" class="img-responsive" style="height:120px;width:100%;object-fit:cover;border-radius:4px;">
                                        <div style="font-weight:bold;margin-top:6px;color:#333;">{{ $rel->name }}</div>
                                    </a>
                                    <span style="color:#e11d48;font-weight:bold;">{{ vnd($rel->effectivePrice()) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </section>
                </div>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('frontend/js/jquery.etalage.min.js') }}"></script>
<script src="{{ asset('frontend/js/easyResponsiveTabs.js') }}" type="text/javascript"></script>
<script>
    jQuery(document).ready(function(đ){
        $('#etalage').etalage({
            thumb_image_width: 300,
            thumb_image_height: 400,
            source_image_width: 900,
            source_image_height: 1200,
            show_hint: true
        });
        $('#horizontalTab').easyResponsiveTabs({
            type: 'default',
            width: 'auto',
            fit: true
        });
    });
</script>
@endpush
