<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;

class ChatbotController extends Controller
{
    public function message(Request $request)
    {
        $message = trim($request->input('message', ''));
        if (empty($message)) {
            return response()->json([
                'reply' => 'Chào bạn! Mình có thể giúp gì cho bạn hôm nay? Hãy thử hỏi về sản phẩm, mã giảm giá hoặc chính sách của cửa hàng nhé!',
                'products' => [],
            ]);
        }

        $lower = mb_strtolower($message, 'UTF-8');

        // 1. Ý định ưu tiên: Tra cứu mã đơn hàng theo thời gian thực (Local DB)
        if (preg_match('/ORD-[\d-]+/i', $message, $matches)) {
            $orderCode = strtoupper($matches[0]);
            $order = Order::where('order_code', $orderCode)->first();
            if ($order) {
                $statusMap = [
                    'ordered' => 'Mới đặt (Đang chờ duyệt)',
                    'processing' => 'Đang đóng gói hàng tại kho',
                    'shipping' => 'Đang vận chuyển giao hàng',
                    'delivered' => 'Đã giao thành công',
                    'canceled' => 'Đã hủy',
                ];
                $st = $statusMap[$order->status] ?? $order->status;
                $shipper = $order->shipper_name ? " (Shipper: {$order->shipper_name} - {$order->shipper_phone})" : "";
                $reply = "📦 Thông tin đơn hàng **{$orderCode}**:\n- Người nhận: {$order->name}\n- Trạng thái: **{$st}**{$shipper}\n- Tổng tiền: " . vnd($order->total) . "\n- Ghi chú: {$order->tracking_note}";
                return response()->json(['reply' => $reply, 'products' => []]);
            }
        }

        // 2. TÍCH HỢP GEMINI AI (Nếu có GEMINI_API_KEY trong .env)
        $apiKey = config('services.gemini.api_key');
        $model = config('services.gemini.model', 'gemini-1.5-flash');

        if (!empty($apiKey)) {
            $geminiReply = $this->callGeminiAPI($message, $apiKey, $model);
            if (!empty($geminiReply)) {
                $products = $this->getRelevantProductCards($message);
                return response()->json([
                    'reply' => $geminiReply,
                    'products' => $products,
                ]);
            }
        }

        // 3. FALLBACK CỤC BỘ (Không cần API key, hoạt động 100% offline/local)
        return $this->handleLocalRuleBased($message, $lower);
    }

    /**
     * Gọi Google Gemini API để tư vấn thời trang thông minh
     */
    private function callGeminiAPI($message, $apiKey, $model)
    {
        try {
            $coupons = Coupon::where('expiry_date', '>=', now()->format('Y-m-d'))->get(['code', 'type', 'value', 'cart_value']);
            $couponText = $coupons->map(function ($c) {
                $val = $c->type == 'percent' ? $c->value . '%' : vnd($c->value);
                return "- Mã {$c->code}: Giảm {$val} cho đơn từ " . vnd($c->cart_value);
            })->implode("\n");

            $products = Product::where('stock_status', 'instock')->take(12)->get(['id', 'name', 'regular_price', 'sale_price', 'gender', 'slug']);
            $productCatalog = $products->map(function ($p) {
                $price = $p->sale_price ?? $p->regular_price;
                return "- {$p->name} ({$p->gender}) - Giá: " . vnd($price) . " (Link: " . route('product.single', $p->slug) . ")";
            })->implode("\n");

            $systemInstruction = "Bạn là Trợ lý ảo AI tư vấn mua sắm thông minh của website thời trang Fashion Store.
PHẠM VI & QUY TẮC PHỤC VỤ (SCOPE):
1. Bạn CHỈ hỗ trợ tư vấn thời trang, phong cách phối đồ, cách chọn size, tìm sản phẩm trên web, giải thích mã voucher và chính sách của Fashion Store.
2. Nếu người dùng hỏi những câu không liên quan đến thời trang hay mua sắm (ví dụ chính trị, lập trình, làm bài tập về nhà...), hãy từ chối lịch sự và khéo léo mời họ quay lại khám phá thời trang.
3. Luôn trả lời bằng tiếng Việt thân thiện, lịch sự, xưng hô 'mình' và 'bạn', chèn emoji sinh động.
4. Trả lời súc tích, gãy gọn, không dài dòng lê thê.

DỮ LIỆU CỬA HÀNG HIỆN TẠI:
- Mã giảm giá đang chạy:
{$couponText}
- Danh sách sản phẩm tiêu biểu:
{$productCatalog}
- Chính sách cửa hàng: Đổi trả hàng trong 07 ngày tại mục 'Tài khoản > Đơn hàng'; Giao hàng nhanh 1-2 ngày (TP.HCM), 2-4 ngày (toàn quốc); Thanh toán COD, MoMo, VNPay, Chuyển khoản VietQR.";

            $response = Http::timeout(10)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}",
                [
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $systemInstruction . "\n\nKhách hàng hỏi: " . $message]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 600,
                    ]
                ]
            );

            if ($response->successful()) {
                $reply = $response->json('candidates.0.content.parts.0.text');
                if (!empty($reply)) {
                    return trim($reply);
                }
            }
        } catch (\Exception $e) {
            Log::warning('Gemini Chatbot API Exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Tìm các sản phẩm liên quan để hiển thị dạng thẻ card trực quan
     */
    private function getRelevantProductCards($text)
    {
        $lower = mb_strtolower($text, 'UTF-8');
        $query = Product::with('category')->where('stock_status', 'instock');
        $hasFilter = false;

        if (str_contains($lower, 'nam') || str_contains($lower, 'đàn ông') || str_contains($lower, 'men')) {
            $query->where(function ($q) {
                $q->where('gender', 'men')->orWhere('name', 'like', '%nam%');
            });
            $hasFilter = true;
        } elseif (str_contains($lower, 'nữ') || str_contains($lower, 'phụ nữ') || str_contains($lower, 'women') || str_contains($lower, 'đầm') || str_contains($lower, 'váy')) {
            $query->where(function ($q) {
                $q->where('gender', 'women')->orWhere('name', 'like', '%nữ%')->orWhere('name', 'like', '%đầm%')->orWhere('name', 'like', '%váy%');
            });
            $hasFilter = true;
        }

        if (str_contains($lower, 'sale') || str_contains($lower, 'giảm giá') || str_contains($lower, 'flash sale')) {
            $query->where(function ($q) {
                $q->where('is_flash_sale', true)->orWhereNotNull('sale_price');
            });
            $hasFilter = true;
        }

        if (str_contains($lower, 'áo khoác') || str_contains($lower, 'jacket') || str_contains($lower, 'denim')) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%khoác%')->orWhere('name', 'like', '%jacket%')->orWhere('name', 'like', '%denim%');
            });
            $hasFilter = true;
        } elseif (str_contains($lower, 'sơ mi') || str_contains($lower, 'shirt')) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%sơ mi%')->orWhere('name', 'like', '%shirt%')->orWhere('name', 'like', '%oxford%');
            });
            $hasFilter = true;
        } elseif (str_contains($lower, 'đầm') || str_contains($lower, 'váy') || str_contains($lower, 'dress')) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%đầm%')->orWhere('name', 'like', '%dress%')->orWhere('name', 'like', '%lụa%');
            });
            $hasFilter = true;
        } elseif (str_contains($lower, 'áo thun') || str_contains($lower, 't-shirt') || str_contains($lower, 'tee')) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%thun%')->orWhere('name', 'like', '%tee%');
            });
            $hasFilter = true;
        }

        if (!$hasFilter) {
            return [];
        }

        $items = $query->take(3)->get();
        $cards = [];
        foreach ($items as $p) {
            $cards[] = [
                'id' => $p->id,
                'name' => $p->name,
                'price' => vnd($p->effectivePrice()),
                'regular_price' => vnd($p->regular_price),
                'image' => asset($p->image),
                'url' => route('product.single', $p->slug),
            ];
        }
        return $cards;
    }

    /**
     * Bộ lọc RAG & Semantic Intent cục bộ khi không có API Key
     */
    private function handleLocalRuleBased($message, $lower)
    {
        $products = [];

        // 1. Voucher / Mã giảm giá
        if (str_contains($lower, 'voucher') || str_contains($lower, 'mã giảm') || str_contains($lower, 'khuyến mãi') || str_contains($lower, 'uu dai') || str_contains($lower, 'ưu đãi') || str_contains($lower, 'code')) {
            $coupons = Coupon::where('expiry_date', '>=', now()->format('Y-m-d'))->get();
            if ($coupons->count() > 0) {
                $reply = "🎟️ Cửa hàng đang có các mã ưu đãi áp dụng được ngay hôm nay:\n";
                foreach ($coupons as $cp) {
                    $val = $cp->type == 'percent' ? $cp->value . '%' : vnd($cp->value);
                    $reply .= "• Mã **{$cp->code}**: Giảm {$val} cho đơn từ " . vnd($cp->cart_value) . " (HSD: {$cp->expiry_date})\n";
                }
                $reply .= "\n👉 Bạn chỉ cần copy mã và dán vào ô Voucher ở trang Giỏ hàng nhé!";
            } else {
                $reply = "Hiện tại các mã ưu đãi đang được cập nhật, bạn nhớ theo dõi trang chủ để đón đợt khuyến mãi sắp tới nhé!";
            }
            return response()->json(['reply' => $reply, 'products' => []]);
        }

        // 2. Chính sách đổi trả
        if (str_contains($lower, 'đổi trả') || str_contains($lower, 'tra hang') || str_contains($lower, 'doi tra') || str_contains($lower, 'hoàn tiền') || str_contains($lower, 'size')) {
            $reply = "🔄 **Chính sách Đổi / Trả hàng**:\n- Hỗ trợ đổi size, đổi màu hoặc trả hàng hoàn tiền trong vòng **07 ngày** kể từ khi nhận hàng.\n- Sản phẩm giữ nguyên tem mác, chưa qua sử dụng.\n- Bạn có thể gửi yêu cầu trực tiếp trong mục **Tài khoản > Chi tiết đơn hàng**.";
            return response()->json(['reply' => $reply, 'products' => []]);
        }

        // 3. Vận chuyển
        if (str_contains($lower, 'vận chuyển') || str_contains($lower, 'giao hàng') || str_contains($lower, 'ship') || str_contains($lower, 'bao lâu')) {
            $reply = "🚚 **Chính sách Giao nhận hàng**:\n- Nội thành TP.HCM: Giao nhanh 1 - 2 ngày làm việc.\n- Các tỉnh thành khác: Giao từ 2 - 4 ngày làm việc.\n- Đơn hàng có nhân viên Shipper chuyên nghiệp và mã vận đơn theo dõi trực tuyến 24/7.";
            return response()->json(['reply' => $reply, 'products' => []]);
        }

        // 4. Thanh toán
        if (str_contains($lower, 'thanh toán') || str_contains($lower, 'chuyển khoản') || str_contains($lower, 'vnpay') || str_contains($lower, 'momo') || str_contains($lower, 'cod')) {
            $reply = "💳 **Phương thức thanh toán hỗ trợ**:\n1. Thanh toán khi nhận hàng (COD)\n2. Cổng VNPay-QR trực tuyến\n3. Ví điện tử MoMo\n4. Chuyển khoản ngân hàng VietQR tự động 24/7.";
            return response()->json(['reply' => $reply, 'products' => []]);
        }

        // 5. Tìm kiếm sản phẩm
        $cards = $this->getRelevantProductCards($message);
        if (!empty($cards)) {
            $reply = "Dưới đây là một số sản phẩm thời trang nổi bật phù hợp với nhu cầu của bạn:";
            return response()->json(['reply' => $reply, 'products' => $cards]);
        }

        // 6. Chào hỏi
        if (str_contains($lower, 'chào') || str_contains($lower, 'hi') || str_contains($lower, 'hello') || str_contains($lower, 'bạn là ai') || str_contains($lower, 'ai đó')) {
            $reply = "Xin chào! Mình là **Trợ lý ảo AI của Fashion Store** 🤖.\n\nMình có thể hỗ trợ bạn:\n- Tư vấn phong cách & gợi ý quần áo thời trang\n- Cung cấp mã giảm giá & voucher mới nhất\n- Giải đáp chính sách đổi trả, giao hàng 24/7\n- Tra cứu trạng thái đơn hàng (Ví dụ: *ORD-20261026-908*)";
            return response()->json(['reply' => $reply, 'products' => []]);
        }

        // 7. Mặc định: Gợi ý sản phẩm nổi bật
        $trending = Product::where('featured', true)->take(2)->get();
        foreach ($trending as $p) {
            $products[] = [
                'id' => $p->id,
                'name' => $p->name,
                'price' => vnd($p->effectivePrice()),
                'regular_price' => vnd($p->regular_price),
                'image' => asset($p->image),
                'url' => route('product.single', $p->slug),
            ];
        }

        $reply = "Cảm ơn bạn đã nhắn tin! Để mình tư vấn nhanh nhất, bạn có thể nhập: *'Đồ nam'*, *'Đầm nữ'*, *'Mã giảm giá'* hoặc *'Chính sách đổi trả'*. Bạn cũng có thể tham khảo các mẫu thịnh hành dưới đây:";
        return response()->json(['reply' => $reply, 'products' => $products]);
    }
}
