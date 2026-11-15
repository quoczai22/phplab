<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>BikeGo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <!-- ===== HEADER ===== -->
    <div class="text-center py-3 border-bottom">
        <h2>&#128690; BikeGo</h2>
    </div>

    <!-- ===== NAVIGATION + BANNER ===== -->
    <section class="banner">
        <!-- hiệu ứng vòng nước -->
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>

        <!-- MENU -->
        <nav class="menu">
            <ul class="menu-list">
                <li class="mb-1">
                    <a class="fs-5 text-white text-decoration-none" href="{{ url('/') }}">
                        &#127968; Trang chủ
                    </a>
                </li>
                <li class="mb-1">
                    <a class="fs-5 text-white text-decoration-none" href="{{ route('stations.index') }}">
                        &#128205; Trạm xe
                    </a>
                </li>
                <li class="mb-1">
                    <a class="fs-5 text-white text-decoration-none" href="{{ route('reviews.index') }}">
                        &#11088; Đánh giá
                    </a>
                </li>

                @auth
                    {{-- Khi ĐÃ ĐĂNG NHẬP: Nếu là Admin thì bấm vào tên sẽ vào thẳng trang Quản trị, nếu là User thì vào Lịch sử thuê xe --}}
                    <li class="mb-1">
                        <a class="fs-5 text-white text-decoration-none"
                           href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('rental.history') }}">
                            &#128100; {{ Auth::user()->name }}
                        </a>
                    </li>
                    <li class="mb-1">
                        <a class="fs-5 text-white text-decoration-none" 
                           href="#" 
                           onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                            &#128682; Đăng xuất
                        </a>
                        <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                @else
                    <!-- Khi CHƯA ĐĂNG NHẬP: Hiển thị Đăng nhập và Đăng ký -->
                    <li class="mb-1">
                        <a class="fs-5 text-white text-decoration-none" href="{{ route('login') }}">
                            &#128273; Đăng nhập
                        </a>
                    </li>
                    <li class="mb-1">
                        <a class="fs-5 text-white text-decoration-none" href="{{ route('register') }}">
                            &#128221; Đăng ký
                        </a>
                    </li>
                @endauth
            </ul>
        </nav>

        <!-- ACTION BUTTON -->
        <div class="action-box">
            <a href="{{ route('rental.index') }}"
                class="action-item p-4 rounded border text-white" style="text-decoration:none"> «« THUÊ XE »» </a>
            <a href="{{ route('rental.return.form') }}"
                class="action-item p-4 rounded border text-white" style="text-decoration:none"> «« TRẢ XE »» </a>
        </div>
    </section>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="container mt-4 min-vh-100">

        {{-- THÔNG BÁO --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- CONTENT --}}
        @yield('content')

    </main>

    <!-- ===== FOOTER ===== -->
    <div class="footer border-top py-3">
        <div class="container">
            <div class="row align-items-center text-center text-md-start">
                <!-- Logo -->
                <div class="col-md-2 mb-2 mb-md-0">
                    <img src="{{ asset('images/logo_thanh_doan.webp') }}" class="img-fluid" style="max-width:80px;">
                </div>

                <!-- Text -->
                <div class="col-md-10">
                    <p class="mb-1">© 2026 - Bản quyền thuộc về thành đoàn tp.HCM</p>
                    <p class="mb-1">Địa chỉ liên hệ: Số 1 Phạm Ngọc Thạch, Quận 1, TP HCM</p>
                    <p class="mb-0">Email: thanhdoan@tphcm.gov.vn</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ==== Bootstrap ==== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>