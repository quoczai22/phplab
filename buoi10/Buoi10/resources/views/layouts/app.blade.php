<!DOCTYPE html>
<html lang="vi">
<head>
<title>@yield('title', 'Fashion Store - Thời Trang Cao Cấp')</title>
<link href="{{ asset('frontend/css/bootstrap.css') }}" rel="stylesheet" type="text/css" media="all" />
<script src="{{ asset('frontend/js/jquery.min.js') }}"></script>
<link href="{{ asset('frontend/css/style.css') }}" rel="stylesheet" type="text/css" media="all" />	
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href='https://fonts.googleapis.com/css?family=Libre+Baskerville:400,700,400italic' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Oswald:400,700,300' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
<style>
    body { font-family: 'Open Sans', sans-serif; color: #333; }
    .badge-cart { background: #ff5722; color: #fff; padding: 2px 7px; border-radius: 10px; font-size: 11px; font-weight: bold; }
    .top-announcement { background: #111827; color: #d1d5db; font-size: 12px; padding: 7px 0; border-bottom: 1px solid #1f2937; }
    .top-announcement a { color: #93c5fd; text-decoration: none; margin-left: 10px; }
    .top-announcement a:hover { color: #fff; text-decoration: underline; }
    .flash-badge { position: absolute; top: 10px; left: 10px; background: #e11d48; color: #fff; padding: 4px 10px; font-size: 11px; font-weight: 700; border-radius: 4px; text-transform: uppercase; z-index: 5; }
    .wishlist-btn { display: inline-block; padding: 5px 10px; background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3; border-radius: 3px; font-size: 12px; text-decoration: none !important; margin-left: 5px; }
    .wishlist-btn:hover { background: #e11d48; color: #fff; }
    .custom-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 6px; padding: 22px; margin-bottom: 22px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .noti-dropdown { position: relative; display: inline-block; }
    .noti-menu { display: none; position: absolute; right: 0; top: 100%; width: 330px; background: #fff; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px rgba(0,0,0,0.15); z-index: 9999; padding: 12px; border-radius: 6px; text-align: left; }
    .noti-dropdown:hover .noti-menu { display: block; }
    .price-vnd { font-weight: bold; color: #e11d48; font-size: 15px; }
    .price-old { color: #9ca3af; text-decoration: line-through; font-size: 12px; margin-left: 6px; }
</style>
@stack('styles')
</head>
<body>
    @php
        $cartItems = session('cart', []);
        $cartCount = array_sum(array_column($cartItems, 'quantity'));
        $wishlistCount = auth()->check() ? \App\Models\Wishlist::where('user_id', auth()->id())->count() : 0;
        $userNotis = auth()->check() ? \App\Models\OrderNotification::where('user_id', auth()->id())->where('for_admin', false)->latest()->take(5)->get() : collect();
        $unreadNotiCount = $userNotis->where('is_read', false)->count();
    @endphp

    <!-- Thanh thông báo đầu trang (Hotline & Hỗ trợ chuẩn TMĐT) -->
    <div class="top-announcement">
        <div class="container">
            <div class="pull-left">
                <span>📞 Hotline: <strong>1900 6868</strong> (08:00 - 22:00)</span>
                <span style="margin-left:15px;">📍 Miễn phí đổi trả trong 7 ngày</span>
                <span style="margin-left:15px;">⚡ Giao hàng toàn quốc</span>
            </div>
            <div class="pull-right">
                <a href="{{ route('order.track') }}">Tra cứu đơn hàng</a>
                <a href="{{ route('wishlist.index') }}">Yêu thích ({{ $wishlistCount }})</a>
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" style="color:#f43f5e;font-weight:bold;">⚙️ Quản trị hệ thống</a>
                    @elseif(auth()->user()->role === 'staff')
                        <a href="{{ route('shipper.app') }}" style="color:#4ade80;font-weight:bold;">🚚 Giao nhận (Shipper)</a>
                    @endif
                @endauth
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <!-- Header -->
    <div class="header">
        <div class="header-top">
            <div class="container">
                <div class="header-grid">
                    <ul>
                        <li><a href="{{ route('articles') }}" class="scroll">Tin Tức & Xu Hướng</a></li>
                        <li><a href="{{ route('contact') }}" class="scroll">Liên Hệ & Góp Ý</a></li>
                        <li><a href="{{ route('privacy') }}" class="scroll">Chính Sách & Quy Định</a></li>
                        <li><a href="{{ route('order.track') }}" class="scroll">Theo Dõi Vận Đơn</a></li>
                        <li><a href="{{ route('cart.index') }}" class="scroll" style="color:#ff5722;font-weight:bold;">🛒 Giỏ hàng <span class="badge-cart">{{ $cartCount }}</span></a></li>
                    </ul>
                </div>
                <div class="header-grid-right">
                    @guest
                        <a href="{{ route('login') }}" class="sign-in" style="font-weight:600;">Đăng Nhập</a>
                        <label>|</label>
                        <a href="{{ route('register') }}" class="sign-up" style="font-weight:600;">Đăng Ký</a>
                    @else
                        <div class="noti-dropdown" style="margin-right: 15px;">
                            <a href="{{ route('account.profile') }}" style="color:#333;font-weight:600;text-decoration:none;">
                                🔔 Thông báo <span class="badge-cart">{{ $unreadNotiCount }}</span>
                            </a>
                            <div class="noti-menu">
                                <div style="font-weight:bold;border-bottom:1px solid #eee;padding-bottom:6px;margin-bottom:8px;font-size:13px;">Thông báo đơn hàng</div>
                                @forelse($userNotis as $noti)
                                    <div style="padding:6px 0;border-bottom:1px dashed #f1f5f9;font-size:12px;">
                                        <a href="{{ route('notification.read', $noti->id) }}" style="color:#111;font-weight:{{ $noti->is_read ? 'normal' : 'bold' }};display:block;">
                                            {{ $noti->title }}
                                        </a>
                                        <span style="color:#64748b;font-size:11px;">{{ $noti->message }}</span>
                                    </div>
                                @empty
                                    <p style="font-size:12px;color:#94a3b8;margin:0;">Chưa có thông báo nào mới.</p>
                                @endforelse
                            </div>
                        </div>
                        <a href="{{ route('account.profile') }}" class="sign-in" style="width:auto;padding:0 8px;font-weight:600;">
                            👤 {{ auth()->user()->name }}
                        </a>
                        <label>|</label>
                        <a href="{{ route('logout') }}" class="sign-up" style="width:auto;padding:0 8px;">Đăng xuất</a>
                    @endguest
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>
        <div class="container">
            <div class="header-bottom">			
                <div class="logo">
                    <a href="{{ route('home') }}"><img src="{{ asset('frontend/images/logo.png') }}" alt="Fashion Store"></a>
                </div>
                <div class="ad-right">
                    <a href="{{ route('flash.sale') }}"><img class="img-responsive" src="{{ asset('frontend/images/ad.png') }}" alt="Siêu Giảm Giá"></a>
                </div>
                <div class="clearfix"> </div>
            </div>	
            <div class="header-bottom-bottom">
                <div class="top-nav">
                    <span class="menu"> </span>
                    <ul>
                        <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}">TRANG CHỦ</a></li>
                        <li class="{{ request()->routeIs('men') ? 'active' : '' }}"><a href="{{ route('men') }}">THỜI TRANG NAM</a></li>
                        <li class="{{ request()->routeIs('women') ? 'active' : '' }}"><a href="{{ route('women') }}">THỜI TRANG NỮ</a></li>
                        <li class="{{ request()->routeIs('collection') ? 'active' : '' }}"><a href="{{ route('collection') }}">BỘ SƯU TẬP</a></li>
                        <li class="{{ request()->routeIs('flash.sale') ? 'active' : '' }}"><a href="{{ route('flash.sale') }}">FLASH SALE</a></li>
                        <li class="{{ request()->routeIs('cart.*') || request()->routeIs('checkout.*') ? 'active' : '' }}"><a href="{{ route('cart.index') }}">GIỎ HÀNG ({{ $cartCount }})</a></li>
                    </ul>	
                    <script>
                        $("span.menu").click(function(){
                            $(".top-nav ul").slideToggle(500, function(){});
                        });
                    </script>
                    <div class="clearfix"> </div>					
                </div>
                <div class="search">
                    <form action="{{ route('collection') }}" method="GET">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm kiếm sản phẩm, thương hiệu...">
                        <input type="submit" value="">
                    </form>
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>
    </div>

    <!-- Thông báo hệ thống -->
    <div class="container" style="margin-top: 15px;">
        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:15px;">
                ✔ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger" style="margin-bottom:15px;">
                ✖ {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom:15px;">
                <ul style="margin:0;padding-left:18px;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Nội dung chính -->
    @yield('content')

    <!-- Footer -->
    <div class="footer">
        <div class="container">
            <div class="footer-class">
                <div class="class-footer">
                    <ul>
                        <li><a href="{{ route('home') }}" class="scroll">TRANG CHỦ</a><label>|</label></li>
                        <li><a href="{{ route('men') }}" class="scroll">THỜI TRANG NAM</a><label>|</label></li>
                        <li><a href="{{ route('women') }}" class="scroll">THỜI TRANG NỮ</a><label>|</label></li>
                        <li><a href="{{ route('collection') }}" class="scroll">BỘ SƯU TẬP</a><label>|</label></li>
                        <li><a href="{{ route('flash.sale') }}" class="scroll">FLASH SALE</a><label>|</label></li>
                        <li><a href="{{ route('order.track') }}" class="scroll">TRA CỨU ĐƠN HÀNG</a></li>
                    </ul>
                    <p class="footer-grid">&copy; 2026 Fashion Store E-Commerce. Bản quyền thuộc về Fashion Store Việt Nam.</p>
                </div>	 
                <div class="footer-left">
                    <a href="{{ route('home') }}"><img src="{{ asset('frontend/images/logo1.png') }}" alt="Logo"></a>
                </div> 
                <div class="clearfix"> </div>
            </div>
        </div>
    </div>

    <!-- CHỨC NĂNG NHÓM C: AI CHATBOT TƯ VẤN SẢN PHẨM & RAG HỎI ĐÁP -->
    <div id="aiChatbotContainer">
        <!-- Nút tròn mở Chatbot -->
        <button id="aiChatbotToggleBtn" onclick="toggleAIChat()" style="position:fixed;bottom:24px;right:24px;width:60px;height:60px;border-radius:50%;background:#ff5722;color:#fff;border:none;box-shadow:0 8px 24px rgba(255,87,34,0.4);font-size:26px;cursor:pointer;z-index:99999;display:flex;align-items:center;justify-content:center;transition:transform 0.2s;" title="Trợ lý ảo AI tư vấn mua sắm">
            💬
        </button>

        <!-- Khung cửa sổ Chatbot -->
        <div id="aiChatWindow" style="position:fixed;bottom:95px;right:24px;width:370px;max-width:calc(100vw - 30px);height:520px;max-height:calc(100vh - 120px);background:#fff;border-radius:16px;box-shadow:0 12px 40px rgba(0,0,0,0.2);border:1px solid #e2e8f0;display:none;flex-direction:column;z-index:99999;overflow:hidden;font-family:inherit;">
            <!-- Header -->
            <div style="background:linear-gradient(135deg, #ff5722, #ea580c);color:#fff;padding:14px 18px;display:flex;align-items:center;justify-content:between;">
                <div style="flex-grow:1;">
                    <div style="font-weight:bold;font-size:15px;">🤖 Trợ Lý Ảo AI (Fashion Assistant)</div>
                    <small style="opacity:0.9;font-size:12px;">● Đang trực tuyến • Sẵn sàng hỗ trợ 24/7</small>
                </div>
                <button onclick="toggleAIChat()" style="background:none;border:none;color:#fff;font-size:20px;cursor:pointer;line-height:1;">✖</button>
            </div>

            <!-- Gợi ý câu hỏi nhanh (Quick Prompts) -->
            <div style="background:#f8fafc;padding:8px 12px;border-bottom:1px solid #e2e8f0;overflow-x:auto;white-space:nowrap;scrollbar-width:none;">
                <button onclick="sendQuickPrompt('Săn mã giảm giá hôm nay')" class="btn btn-xs btn-default" style="border-radius:12px;margin-right:4px;">🎟️ Mã giảm giá</button>
                <button onclick="sendQuickPrompt('Tư vấn thời trang nam')" class="btn btn-xs btn-default" style="border-radius:12px;margin-right:4px;">👔 Đồ nam</button>
                <button onclick="sendQuickPrompt('Gợi ý đầm dạ tiệc nữ')" class="btn btn-xs btn-default" style="border-radius:12px;margin-right:4px;">👗 Đầm nữ</button>
                <button onclick="sendQuickPrompt('Chính sách đổi trả hàng')" class="btn btn-xs btn-default" style="border-radius:12px;">🔄 Đổi trả</button>
            </div>

            <!-- Khung nội dung tin nhắn -->
            <div id="aiChatMessages" style="flex:1;padding:15px;overflow-y:auto;background:#f8fafc;display:flex;flex-direction:column;gap:12px;font-size:13px;">
                <div style="align-self:flex-start;max-width:85%;background:#fff;padding:12px 14px;border-radius:14px 14px 14px 2px;box-shadow:0 1px 3px rgba(0,0,0,0.06);color:#1e293b;line-height:1.5;">
                    Chào bạn! Mình là <strong>Trợ lý ảo AI của Fashion Store</strong> 🤖.<br>
                    Mình có thể giúp bạn tìm kiếm quần áo theo nhu cầu, tra cứu mã ưu đãi hoặc hỗ trợ chính sách đổi trả hàng 24/7. Bạn cần tìm trang phục gì hôm nay?
                </div>
            </div>

            <!-- Ô nhập tin nhắn -->
            <div style="padding:10px 12px;background:#fff;border-top:1px solid #e2e8f0;display:flex;align-items:center;gap:8px;">
                <input type="text" id="aiChatInput" placeholder="Nhập câu hỏi của bạn (VD: Tìm áo khoác, mã giảm giá...)" style="flex:1;border:1px solid #cbd5e1;padding:10px 14px;border-radius:24px;outline:none;font-size:13px;" onkeypress="if(event.key === 'Enter') submitAIChatMessage()">
                <button onclick="submitAIChatMessage()" style="width:40px;height:40px;border-radius:50%;background:#ff5722;color:#fff;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:15px;">
                    ➤
                </button>
            </div>
        </div>
    </div>

    <script>
        function toggleAIChat() {
            var box = document.getElementById('aiChatWindow');
            if (box.style.display === 'none' || box.style.display === '') {
                box.style.display = 'flex';
                document.getElementById('aiChatInput').focus();
            } else {
                box.style.display = 'none';
            }
        }

        function sendQuickPrompt(text) {
            document.getElementById('aiChatInput').value = text;
            submitAIChatMessage();
        }

        function appendMessage(sender, text, products) {
            var container = document.getElementById('aiChatMessages');
            var msgDiv = document.createElement('div');
            
            if (sender === 'user') {
                msgDiv.style.cssText = 'align-self:flex-end;max-width:82%;background:#ff5722;color:#fff;padding:10px 14px;border-radius:14px 14px 2px 14px;box-shadow:0 1px 3px rgba(0,0,0,0.1);line-height:1.4;';
                msgDiv.innerText = text;
            } else {
                msgDiv.style.cssText = 'align-self:flex-start;max-width:88%;background:#fff;padding:12px 14px;border-radius:14px 14px 14px 2px;box-shadow:0 1px 3px rgba(0,0,0,0.06);color:#1e293b;line-height:1.5;';
                // Chuyển markdown bold ** sang <strong> và xuống dòng
                var formatted = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/\n/g, '<br>');
                msgDiv.innerHTML = formatted;

                if (products && products.length > 0) {
                    var prodContainer = document.createElement('div');
                    prodContainer.style.cssText = 'margin-top:10px;display:flex;flex-direction:column;gap:8px;';
                    products.forEach(function(p) {
                        var pCard = document.createElement('div');
                        pCard.style.cssText = 'display:flex;align-items:center;gap:10px;background:#f8fafc;padding:8px;border-radius:8px;border:1px solid #e2e8f0;';
                        pCard.innerHTML = '<img src="' + p.image + '" style="width:48px;height:55px;object-fit:cover;border-radius:4px;">' +
                            '<div style="flex:1;min-width:0;">' +
                                '<div style="font-weight:bold;font-size:12px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + p.name + '</div>' +
                                '<div style="color:#e11d48;font-weight:bold;font-size:12px;">' + p.price + '</div>' +
                                '<a href="' + p.url + '" style="font-size:11px;color:#ff5722;font-weight:600;text-decoration:none;">Xem chi tiết →</a>' +
                            '</div>';
                        prodContainer.appendChild(pCard);
                    });
                    msgDiv.appendChild(prodContainer);
                }
            }

            container.appendChild(msgDiv);
            container.scrollTop = container.scrollHeight;
        }

        function submitAIChatMessage() {
            var input = document.getElementById('aiChatInput');
            var text = input.value.trim();
            if (!text) return;

            appendMessage('user', text);
            input.value = '';

            // Indicator loading
            var container = document.getElementById('aiChatMessages');
            var loadingId = 'ai-loading-' + Date.now();
            var loadingDiv = document.createElement('div');
            loadingDiv.id = loadingId;
            loadingDiv.style.cssText = 'align-self:flex-start;background:#fff;padding:8px 14px;border-radius:14px;color:#94a3b8;font-size:12px;font-style:italic;';
            loadingDiv.innerText = 'Trợ lý AI đang suy nghĩ và tìm kiếm...';
            container.appendChild(loadingDiv);
            container.scrollTop = container.scrollHeight;

            fetch('{{ route("chatbot.message") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: text })
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                var el = document.getElementById(loadingId);
                if (el) el.remove();
                appendMessage('ai', data.reply, data.products);
            })
            .catch(function(err) {
                var el = document.getElementById(loadingId);
                if (el) el.remove();
                appendMessage('ai', 'Xin lỗi bạn, kết nối trợ lý AI bị gián đoạn một chút. Bạn thử gửi lại tin nhắn giúp mình nhé!');
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
