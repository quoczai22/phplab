<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Address;
use App\Models\Wishlist;
use App\Models\Order;
use App\Models\ReturnRequest;
use App\Models\Review;
use App\Models\OrderNotification;

class AccountController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->latest()->get();
        $addresses = Address::where('user_id', $user->id)->orderByDesc('is_default')->get();
        $returnRequests = ReturnRequest::with('order')->where('user_id', $user->id)->latest()->get();
        $notifications = OrderNotification::where('user_id', $user->id)->where('for_admin', false)->latest()->take(15)->get();

        return view('frontend.profile', compact('user', 'orders', 'addresses', 'returnRequests', 'notifications'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|max:190|unique:users,email,' . $user->id,
        ], [
            'name.required' => 'Họ và tên không được để trống.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.unique' => 'Email này đã có người sử dụng.',
        ]);

        $user->update($request->only('name', 'phone', 'email'));
        return back()->with('success', 'Cập nhật thông tin tài khoản thành công!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu mới phải có tối thiểu 6 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không chính xác.');
        }

        $user->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Đổi mật khẩu thành công!');
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'district' => 'nullable|string|max:100',
        ], [
            'name.required' => 'Vui lòng nhập họ tên người nhận.',
            'phone.required' => 'Vui lòng nhập số điện thoại người nhận.',
            'address.required' => 'Vui lòng nhập số nhà, tên đường chi tiết.',
            'city.required' => 'Vui lòng nhập Tỉnh / Thành phố.',
        ]);

        if ($request->is_default) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        Address::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'district' => $request->district,
            'city' => $request->city,
            'is_default' => $request->has('is_default') || Address::where('user_id', Auth::id())->count() === 0,
        ]);

        return back()->with('success', 'Đã thêm địa chỉ giao nhận mới!');
    }

    public function setDefaultAddress($id)
    {
        Address::where('user_id', Auth::id())->update(['is_default' => false]);
        Address::where('user_id', Auth::id())->where('id', $id)->update(['is_default' => true]);
        return back()->with('success', 'Đã đặt làm địa chỉ giao hàng mặc định.');
    }

    public function deleteAddress($id)
    {
        Address::where('user_id', Auth::id())->where('id', $id)->delete();
        return back()->with('success', 'Đã xóa địa chỉ thành công.');
    }

    public function wishlist()
    {
        $wishlists = Auth::check()
            ? Wishlist::with('product.category')->where('user_id', Auth::id())->latest()->get()
            : collect();
        return view('frontend.wishlist', compact('wishlists'));
    }

    public function toggleWishlist($product_id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để lưu sản phẩm vào danh sách yêu thích.');
        }

        $existing = Wishlist::where('user_id', Auth::id())->where('product_id', $product_id)->first();
        if ($existing) {
            $existing->delete();
            return back()->with('success', 'Đã xóa sản phẩm khỏi danh sách yêu thích.');
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $product_id,
        ]);

        return back()->with('success', 'Đã thêm sản phẩm vào danh sách yêu thích!');
    }

    public function trackOrder(Request $request)
    {
        $order = null;
        if ($request->filled('order_code')) {
            $code = trim($request->order_code);
            $order = Order::with('items.product', 'returnRequests')
                ->where('order_code', $code)
                ->orWhere('id', $code)
                ->first();
        }
        return view('frontend.track-order', compact('order'));
    }

    public function orderDetail($order_code)
    {
        $order = Order::with('items.product', 'returnRequests')
            ->where('order_code', $order_code)
            ->orWhere('id', $order_code)
            ->firstOrFail();

        return view('frontend.order-detail', compact('order'));
    }

    public function submitReturn(Request $request, $order_id)
    {
        $request->validate([
            'type' => 'required|in:return,exchange',
            'reason' => 'required|string|max:500',
        ], [
            'type.required' => 'Vui lòng chọn hình thức đổi hoặc trả hàng.',
            'reason.required' => 'Vui lòng cung cấp lý do chi tiết để cửa hàng hỗ trợ nhanh nhất.',
        ]);

        $order = Order::findOrFail($order_id);

        ReturnRequest::create([
            'order_id' => $order->id,
            'user_id' => Auth::id() ?? $order->user_id,
            'type' => $request->type,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        OrderNotification::create([
            'user_id' => Auth::id(),
            'order_id' => $order->id,
            'title' => 'Yêu cầu Đổi/Trả hàng đơn ' . $order->order_code,
            'message' => 'Khách hàng gửi yêu cầu ' . ($request->type === 'exchange' ? 'Đổi hàng' : 'Trả hàng & Hoàn tiền') . ': ' . $request->reason,
            'type' => 'return',
            'for_admin' => true,
        ]);

        return back()->with('success', 'Đã gửi yêu cầu Đổi/Trả hàng thành công! Bộ phận CSKH sẽ liên hệ hỗ trợ bạn sớm nhất.');
    }

    public function storeReview(Request $request, $product_id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để gửi nhận xét và đánh giá sản phẩm.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'rating.min' => 'Điểm đánh giá tối thiểu là 1 sao.',
            'rating.max' => 'Điểm đánh giá tối đa là 5 sao.',
            'comment.required' => 'Vui lòng nhập nội dung nhận xét sản phẩm.',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => true,
        ]);

        return back()->with('success', 'Cảm ơn bạn đã gửi đánh giá! Nhận xét của bạn rất quý giá với chúng tôi.');
    }

    public function markNotificationRead($id)
    {
        $noti = OrderNotification::findOrFail($id);
        $noti->update(['is_read' => true]);
        if ($noti->order) {
            return redirect()->route('account.order.detail', $noti->order->order_code);
        }
        return back();
    }
}
