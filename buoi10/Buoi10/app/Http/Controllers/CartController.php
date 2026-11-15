<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\OrderNotification;

class CartController extends Controller
{
    
    public function getCartSummary()
    {
        $cart = session()->get('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $discount = 0;
        $coupon = session()->get('coupon');
        if ($coupon) {
            if ($subtotal >= $coupon['cart_value']) {
                if ($coupon['type'] === 'percent') {
                    $discount = round($subtotal * ($coupon['value'] / 100), 2);
                } else {
                    $discount = min($subtotal, (float) $coupon['value']);
                }
            } else {
                session()->forget('coupon');
                $coupon = null;
            }
        }

        $afterDiscount = max(0, $subtotal - $discount);
        $tax = round($afterDiscount * 0.10, 2); // 10% VAT
        $total = round($afterDiscount + $tax, 2);

        return compact('cart', 'subtotal', 'discount', 'tax', 'total', 'coupon');
    }

    public function index()
    {
        $summary = $this->getCartSummary();
        $availableCoupons = Coupon::where('expiry_date', '>=', now()->format('Y-m-d'))->get();
        return view('frontend.cart', array_merge($summary, ['availableCoupons' => $availableCoupons]));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1|max:99',
            'size' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:50',
            'variant_id' => 'nullable|exists:product_variants,id',
        ], [
            'product_id.required' => 'Không tìm thấy sản phẩm.',
            'product_id.exists' => 'Sản phẩm không tồn tại.',
            'quantity.min' => 'Số lượng mua tối thiểu là 1.',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = max(1, (int) ($request->quantity ?? 1));
        $size = $request->size ?? 'M';
        $color = $request->color ?? 'Mặc định';
        $variantId = $request->variant_id ?? null;

        $price = $product->effectivePrice();
        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            if ($variant) {
                $size = $variant->size;
                $color = $variant->color;
                if ($variant->price > 0) {
                    $price = $variant->price;
                }
            }
        }

        $rowId = $product->id . '_' . $size . '_' . $color;
        $cart = session()->get('cart', []);

        if (isset($cart[$rowId])) {
            $cart[$rowId]['quantity'] += $quantity;
        } else {
            $cart[$rowId] = [
                'row_id' => $rowId,
                'product_id' => $product->id,
                'variant_id' => $variantId,
                'name' => $product->name,
                'slug' => $product->slug,
                'image' => $product->image,
                'price' => (float) $price,
                'quantity' => $quantity,
                'size' => $size,
                'color' => $color,
                'options' => "Size: {$size} | Màu: {$color}",
            ];
        }

        session()->put('cart', $cart);

        if ($request->buy_now) {
            return redirect()->route('checkout.index');
        }

        return redirect()->route('cart.index')->with('success', 'Đã thêm "' . $product->name . '" vào giỏ hàng thành công!');
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        if (isset($request->quantities) && is_array($request->quantities)) {
            foreach ($request->quantities as $rowId => $qty) {
                if (isset($cart[$rowId])) {
                    $q = (int) $qty;
                    if ($q <= 0) {
                        unset($cart[$rowId]);
                    } else {
                        $cart[$rowId]['quantity'] = min(99, $q);
                    }
                }
            }
            session()->put('cart', $cart);
        }
        return redirect()->route('cart.index')->with('success', 'Đã cập nhật số lượng trong giỏ hàng!');
    }

    public function remove($rowId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$rowId])) {
            unset($cart[$rowId]);
            session()->put('cart', $cart);
        }
        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50'
        ], [
            'code.required' => 'Vui lòng nhập mã giảm giá.',
        ]);

        $code = strtoupper(trim($request->code));
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return back()->with('error', 'Mã giảm giá "' . $code . '" không tồn tại hoặc đã bị xóa.');
        }
        if ($coupon->expiry_date < now()->format('Y-m-d')) {
            return back()->with('error', 'Mã giảm giá "' . $code . '" đã hết hạn sử dụng.');
        }

        $summary = $this->getCartSummary();
        if ($summary['subtotal'] < $coupon->cart_value) {
            return back()->with('error', 'Đơn hàng tối thiểu phải đạt ' . vnd($coupon->cart_value) . ' để áp dụng mã này.');
        }

        session()->put('coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'cart_value' => $coupon->cart_value,
        ]);

        return back()->with('success', 'Áp dụng mã giảm giá ' . $coupon->code . ' thành công!');
    }

    public function removeCoupon()
    {
        session()->forget('coupon');
        return back()->with('success', 'Đã gỡ mã giảm giá khỏi đơn hàng.');
    }

    public function checkout()
    {
        $summary = $this->getCartSummary();
        if (empty($summary['cart'])) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $addresses = Auth::check() ? Auth::user()->addresses()->orderByDesc('is_default')->get() : collect();
        $defaultAddress = $addresses->first();

        return view('frontend.checkout', array_merge($summary, compact('addresses', 'defaultAddress')));
    }

    public function placeOrder(Request $request)
    {
        $summary = $this->getCartSummary();
        if (empty($summary['cart'])) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'payment_mode' => 'required|in:cod,vnpay,momo,banking',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên người nhận hàng.',
            'phone.required' => 'Vui lòng nhập số điện thoại người nhận.',
            'address.required' => 'Vui lòng nhập địa chỉ giao hàng cụ thể.',
            'city.required' => 'Vui lòng nhập Tỉnh / Thành phố nhận hàng.',
            'payment_mode.required' => 'Vui lòng chọn phương thức thanh toán.',
            'payment_mode.in' => 'Phương thức thanh toán không hợp lệ.',
        ]);

        $orderCode = 'ORD-' . date('Ymd') . '-' . rand(100, 999);

        $order = Order::create([
            'order_code' => $orderCode,
            'user_id' => Auth::id(),
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'subtotal' => $summary['subtotal'],
            'discount' => $summary['discount'],
            'tax' => $summary['tax'],
            'total' => $summary['total'],
            'coupon_code' => $summary['coupon']['code'] ?? null,
            'payment_mode' => $request->payment_mode,
            'payment_status' => 'pending',
            'status' => 'ordered',
            'tracking_note' => 'Đơn hàng mới đã được tạo thành công, chờ kho xử lý đóng gói.',
        ]);

        // Tạo order items
        foreach ($summary['cart'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'variant_id' => $item['variant_id'] ?? null,
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'options' => $item['options'] ?? '',
            ]);

            // Trừ số lượng tồn kho sản phẩm
            Product::where('id', $item['product_id'])->decrement('quantity', $item['quantity']);
            if (!empty($item['variant_id'])) {
                ProductVariant::where('id', $item['variant_id'])->decrement('quantity', $item['quantity']);
            }
        }

        // Lưu địa chỉ nếu được chọn
        if ($request->has('save_address') && Auth::check()) {
            Address::firstOrCreate([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
            ]);
        }

        // Thông báo realtime đơn hàng
        OrderNotification::create([
            'user_id' => Auth::id(),
            'order_id' => $order->id,
            'title' => 'Đặt hàng thành công đơn ' . $order->order_code,
            'message' => 'Cảm ơn quý khách! Đơn hàng của bạn đang được chuẩn bị để bàn giao cho đối tác vận chuyển.',
            'type' => 'order',
            'for_admin' => false,
        ]);

        OrderNotification::create([
            'user_id' => null,
            'order_id' => $order->id,
            'title' => 'Đơn hàng mới: ' . $order->order_code,
            'message' => 'Khách hàng ' . $order->name . ' vừa đặt đơn trị giá ' . vnd($order->total),
            'type' => 'order',
            'for_admin' => true,
        ]);

        // Xóa giỏ hàng và voucher sau khi đặt
        session()->forget('cart');
        session()->forget('coupon');

        // Nếu thanh toán online, điều hướng sang cổng thanh toán trực tuyến
        if (in_array($request->payment_mode, ['vnpay', 'momo', 'banking'])) {
            return redirect()->route('checkout.payment', $order->order_code);
        }

        return redirect()->route('account.order.detail', $order->order_code)->with('success', 'Chúc mừng bạn đã đặt hàng thành công! Mã đơn: ' . $order->order_code);
    }

    public function onlinePayment($order_code)
    {
        $order = Order::where('order_code', $order_code)->firstOrFail();
        return view('frontend.online-payment', compact('order'));
    }

    public function confirmOnlinePayment(Request $request, $order_code)
    {
        $order = Order::where('order_code', $order_code)->firstOrFail();
        $order->update([
            'payment_status' => 'paid',
            'tracking_note' => 'Thanh toán trực tuyến thành công qua ' . strtoupper($order->payment_mode) . '. Đơn hàng đang được đóng gói.',
        ]);

        OrderNotification::create([
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'title' => 'Thanh toán thành công đơn ' . $order->order_code,
            'message' => 'Hệ thống đã nhận được tiền thanh toán qua ' . strtoupper($order->payment_mode),
            'type' => 'order',
            'for_admin' => false,
        ]);

        return redirect()->route('account.order.detail', $order->order_code)->with('success', 'Thanh toán online thành công! Đơn hàng đang được chuẩn bị giao.');
    }
}
