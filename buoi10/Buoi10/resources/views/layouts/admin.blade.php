<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="vi" lang="vi">

<head>
    <title>@yield('title', 'Hệ Thống Quản Trị Cửa Hàng | Fashion Store Admin')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/animate.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/animation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/font/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/icon/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('admin/images/favicon.ico') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/sweetalert.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/custom.css') }}">
    @stack('styles')
</head>

<body class="body">
    @php
        $adminNotis = \App\Models\OrderNotification::where('for_admin', true)->latest()->take(6)->get();
    @endphp
    <div id="wrapper">
        <div id="page" class="">
            <div class="layout-wrap">

                <div class="section-menu-left">
                    <div class="box-logo">
                        <a href="{{ route('admin.dashboard') }}" id="site-logo-inner">
                            <img id="logo_header" alt="Fashion Store" src="{{ asset('frontend/images/logo.png') }}"
                                data-light="{{ asset('frontend/images/logo.png') }}" data-dark="{{ asset('frontend/images/logo.png') }}">
                        </a>
                        <div class="button-show-hide">
                            <i class="icon-menu-left"></i>
                        </div>
                    </div>
                    <div class="center">
                        <div class="center-item">
                            <div class="center-heading">Tổng Quan</div>
                            <ul class="menu-list">
                                <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                    <a href="{{ route('admin.dashboard') }}">
                                        <div class="icon"><i class="icon-grid"></i></div>
                                        <div class="text">Bảng Điều Khiển</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('home') }}" target="_blank">
                                        <div class="icon"><i class="icon-external-link"></i></div>
                                        <div class="text">Xem Website Cửa Hàng</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="center-item">
                            <div class="center-heading">Quản Lý Nghiệp Vụ</div>
                            <ul class="menu-list">
                                <li class="menu-item has-children {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                                    <a href="javascript:void(0);" class="menu-item-button">
                                        <div class="icon"><i class="icon-shopping-cart"></i></div>
                                        <div class="text">Sản Phẩm</div>
                                    </a>
                                    <ul class="sub-menu" style="{{ request()->routeIs('admin.products*') ? 'display:block;' : '' }}">
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.products.add') }}">
                                                <div class="text">Thêm Sản Phẩm Mới</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.products') }}">
                                                <div class="text">Danh Sách Sản Phẩm</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="menu-item {{ request()->routeIs('admin.inventory*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.inventory') }}">
                                        <div class="icon"><i class="icon-box"></i></div>
                                        <div class="text">Biến Thể & Tồn Kho</div>
                                    </a>
                                </li>

                                <li class="menu-item has-children {{ request()->routeIs('admin.brands*') ? 'active' : '' }}">
                                    <a href="javascript:void(0);" class="menu-item-button">
                                        <div class="icon"><i class="icon-layers"></i></div>
                                        <div class="text">Thương Hiệu</div>
                                    </a>
                                    <ul class="sub-menu" style="{{ request()->routeIs('admin.brands*') ? 'display:block;' : '' }}">
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.brands.add') }}">
                                                <div class="text">Thêm Thương Hiệu</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.brands') }}">
                                                <div class="text">Danh Sách Thương Hiệu</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="menu-item has-children {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                                    <a href="javascript:void(0);" class="menu-item-button">
                                        <div class="icon"><i class="icon-layers"></i></div>
                                        <div class="text">Danh Mục</div>
                                    </a>
                                    <ul class="sub-menu" style="{{ request()->routeIs('admin.categories*') ? 'display:block;' : '' }}">
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.categories.add') }}">
                                                <div class="text">Thêm Danh Mục</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.categories') }}">
                                                <div class="text">Danh Sách Danh Mục</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="menu-item has-children {{ request()->routeIs('admin.order*') ? 'active' : '' }}">
                                    <a href="javascript:void(0);" class="menu-item-button">
                                        <div class="icon"><i class="icon-file-plus"></i></div>
                                        <div class="text">Đơn Hàng</div>
                                    </a>
                                    <ul class="sub-menu" style="{{ request()->routeIs('admin.order*') ? 'display:block;' : '' }}">
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.orders') }}">
                                                <div class="text">Danh Sách Đơn Hàng</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.order.tracking') }}">
                                                <div class="text">Theo Dõi Vận Đơn</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="menu-item {{ request()->routeIs('admin.returns*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.returns') }}">
                                        <div class="icon"><i class="icon-refresh-cw"></i></div>
                                        <div class="text">Đổi / Trả Hàng</div>
                                    </a>
                                </li>

                                <li class="menu-item {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.reviews') }}">
                                        <div class="icon"><i class="icon-star"></i></div>
                                        <div class="text">Đánh Giá Sản Phẩm</div>
                                    </a>
                                </li>

                                <li class="menu-item {{ request()->routeIs('admin.sliders*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.sliders') }}">
                                        <div class="icon"><i class="icon-image"></i></div>
                                        <div class="text">Banner & Slider</div>
                                    </a>
                                </li>

                                <li class="menu-item {{ request()->routeIs('admin.coupons*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.coupons') }}">
                                        <div class="icon"><i class="icon-grid"></i></div>
                                        <div class="text">Mã Giảm Giá / Voucher</div>
                                    </a>
                                </li>

                                <li class="menu-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.reports') }}">
                                        <div class="icon"><i class="icon-pie-chart"></i></div>
                                        <div class="text">Báo Cáo Doanh Thu</div>
                                    </a>
                                </li>

                                <li class="menu-item">
                                    <a href="{{ route('shipper.app') }}">
                                        <div class="icon"><i class="icon-smartphone"></i></div>
                                        <div class="text">Giao Diện Shipper</div>
                                    </a>
                                </li>

                                <li class="menu-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.users') }}">
                                        <div class="icon"><i class="icon-user"></i></div>
                                        <div class="text">Tài Khoản & Phân Quyền</div>
                                    </a>
                                </li>

                                <li class="menu-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.settings') }}">
                                        <div class="icon"><i class="icon-settings"></i></div>
                                        <div class="text">Cài Đặt Hệ Thống</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="section-content-right">
                    <div class="header-dashboard">
                        <div class="wrap">
                            <div class="header-left">
                                <a href="{{ route('admin.dashboard') }}">
                                    <img id="logo_header_mobile" alt="Fashion Store" src="{{ asset('frontend/images/logo.png') }}"
                                        data-width="154px" data-height="52px">
                                </a>
                                <div class="button-show-hide">
                                    <i class="icon-menu-left"></i>
                                </div>

                                <form class="form-search flex-grow" action="{{ route('admin.products') }}" method="GET">
                                    <fieldset class="name">
                                        <input type="text" placeholder="Tìm kiếm nhanh tên sản phẩm hoặc mã SKU..." class="show-search" name="name"
                                            value="{{ request('name') }}">
                                    </fieldset>
                                    <div class="button-submit">
                                        <button type="submit"><i class="icon-search"></i></button>
                                    </div>
                                </form>
                            </div>

                            <div class="header-grid">
                                <div class="popup-wrap message type-header">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="header-item">
                                                <span class="text-tiny">{{ $adminNotis->count() }}</span>
                                                <i class="icon-bell"></i>
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end has-content"
                                            aria-labelledby="dropdownMenuButton2">
                                            <li>
                                                <h6>Thông báo đơn hàng mới</h6>
                                            </li>
                                            @forelse($adminNotis as $noti)
                                                <li>
                                                    <div class="message-item item-1">
                                                        <div class="image">
                                                            <i class="icon-noti-1"></i>
                                                        </div>
                                                        <div>
                                                            <div class="body-title-2">{{ $noti->title }}</div>
                                                            <div class="text-tiny">{{ $noti->message }}</div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @empty
                                                <li><div class="text-tiny">Chưa có thông báo mới</div></li>
                                            @endforelse
                                            <li><a href="{{ route('admin.orders') }}" class="tf-button w-full">Xem toàn bộ đơn hàng</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="popup-wrap user type-header">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="header-user wg-user">
                                                <span class="image">
                                                    <img src="{{ asset('admin/images/avatar/user-1.png') }}" alt="">
                                                </span>
                                                <span class="flex flex-column">
                                                    <span class="body-title mb-2">{{ auth()->check() ? auth()->user()->name : 'Admin' }}</span>
                                                    <span class="text-tiny">{{ auth()->check() ? strtoupper(auth()->user()->role) : 'ADMIN' }}</span>
                                                </span>
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end has-content"
                                            aria-labelledby="dropdownMenuButton3">
                                            <li>
                                                <a href="{{ route('admin.settings') }}" class="user-item">
                                                    <div class="icon"><i class="icon-user"></i></div>
                                                    <div class="body-title-2">Cài Đặt Tài Khoản</div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('home') }}" class="user-item">
                                                    <div class="icon"><i class="icon-shopping-bag"></i></div>
                                                    <div class="body-title-2">Xem Cửa Hàng</div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('logout') }}" class="user-item">
                                                    <div class="icon"><i class="icon-log-out"></i></div>
                                                    <div class="body-title-2">Đăng Xuất</div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="main-content">
                        <div class="main-content-inner">
                            <div class="main-content-wrap">
                                @if(session('success'))
                                    <div class="alert alert-success mb-20" style="font-size:14px;padding:12px 18px;">
                                        ✔ {{ session('success') }}
                                    </div>
                                @endif
                                @if(session('error'))
                                    <div class="alert alert-danger mb-20" style="font-size:14px;padding:12px 18px;">
                                        ✖ {{ session('error') }}
                                    </div>
                                @endif
                                @if($errors->any())
                                    <div class="alert alert-danger mb-20" style="font-size:14px;padding:12px 18px;">
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $err)
                                                <li>{{ $err }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @yield('content')
                            </div>
                        </div>

                        <div class="bottom-page">
                            <div class="body-text">Bản quyền © 2026 Fashion Store | Trịnh Hữu Kiến Quốc</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('admin/js/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('admin/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('admin/js/apexcharts/apexcharts.js') }}"></script>
    <script src="{{ asset('admin/js/main.js') }}"></script>
    @stack('scripts')
</body>

</html>
