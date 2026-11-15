<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Coupon;
use App\Models\Slider;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ReturnRequest;
use App\Models\Review;
use App\Models\OrderNotification;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalOrders = Order::count();
        $totalAmount = Order::sum('total');
        $pendingOrders = Order::whereIn('status', ['ordered', 'processing', 'shipping'])->count();
        $pendingAmount = Order::whereIn('status', ['ordered', 'processing', 'shipping'])->sum('total');
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $deliveredAmount = Order::where('status', 'delivered')->sum('total');
        $canceledOrders = Order::where('status', 'canceled')->count();
        $canceledAmount = Order::where('status', 'canceled')->sum('total');

        $recentOrders = Order::with('items')->latest()->take(8)->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalAmount',
            'pendingOrders',
            'pendingAmount',
            'deliveredOrders',
            'deliveredAmount',
            'canceledOrders',
            'canceledAmount',
            'recentOrders'
        ));
    }

    // PRODUCTS
    public function products(Request $request)
    {
        $query = Product::with('category', 'brand', 'variants');
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%')
                  ->orWhere('sku', 'like', '%' . $request->name . '%');
        }
        $products = $query->latest()->paginate(10);
        return view('admin.products', compact('products'));
    }

    public function addProduct()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.add-product', compact('categories', 'brands'));
    }

    public function storeProduct(Request $request)
    {
        // Đồng bộ SKU viết hoa hoặc thường
        if (!$request->has('sku') && $request->has('SKU')) {
            $request->merge(['sku' => $request->SKU]);
        }

        $request->validate([
            'name' => 'required|string|max:150',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lte:regular_price',
            'sku' => 'required|string|max:50|unique:products,sku',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'regular_price.required' => 'Vui lòng nhập giá gốc sản phẩm.',
            'regular_price.numeric' => 'Giá gốc phải là chữ số.',
            'sale_price.lte' => 'Giá khuyến mãi không được lớn hơn giá gốc.',
            'sku.required' => 'Vui lòng nhập mã SKU.',
            'sku.unique' => 'Mã SKU này đã tồn tại trong hệ thống.',
            'quantity.required' => 'Vui lòng nhập số lượng tồn kho.',
            'image.image' => 'File tải lên phải là định dạng hình ảnh.',
        ]);

        $imagePath = 'frontend/images/pi.jpg';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/products'), $filename);
            $imagePath = 'uploads/products/' . $filename;
        }

        $product = Product::create([
            'name' => $request->name,
            'slug' => $request->slug ? Str::slug($request->slug) : Str::slug($request->name) . '-' . rand(10, 99),
            'short_description' => $request->short_description,
            'description' => $request->description,
            'regular_price' => $request->regular_price,
            'sale_price' => $request->sale_price ?: null,
            'sku' => $request->sku,
            'stock_status' => $request->stock_status ?? 'instock',
            'featured' => (bool) $request->featured,
            'quantity' => $request->quantity,
            'image' => $imagePath,
            'images' => 'frontend/images/sin1.jpg,frontend/images/sin2.jpg',
            'category_id' => $request->category_id ?: null,
            'brand_id' => $request->brand_id ?: null,
            'gender' => $request->gender ?? 'women',
            'is_flash_sale' => (bool) $request->is_flash_sale,
            'flash_sale_price' => $request->flash_sale_price ?: null,
            'flash_sale_end' => $request->is_flash_sale ? now()->addDays(7) : null,
        ]);

        // Tạo biến thể mặc định
        $sizes = ['S', 'M', 'L'];
        foreach ($sizes as $sz) {
            ProductVariant::create([
                'product_id' => $product->id,
                'size' => $sz,
                'color' => 'Đen',
                'sku' => $product->sku . '-' . $sz,
                'price' => $product->effectivePrice(),
                'quantity' => max(1, (int) ($product->quantity / 3)),
            ]);
        }

        return redirect()->route('admin.products')->with('success', 'Đã thêm sản phẩm mới thành công!');
    }

    public function editProduct($id)
    {
        $product = Product::with('variants')->findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.edit-product', compact('product', 'categories', 'brands'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if (!$request->has('sku') && $request->has('SKU')) {
            $request->merge(['sku' => $request->SKU]);
        }

        $request->validate([
            'name' => 'required|string|max:150',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|max:50|unique:products,sku,' . $product->id,
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'regular_price.required' => 'Vui lòng nhập giá gốc sản phẩm.',
            'sku.required' => 'Vui lòng nhập mã SKU.',
            'sku.unique' => 'Mã SKU này đã tồn tại.',
            'quantity.required' => 'Vui lòng nhập số lượng tồn kho.',
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/products'), $filename);
            $imagePath = 'uploads/products/' . $filename;
        }

        $product->update([
            'name' => $request->name,
            'slug' => $request->slug ? Str::slug($request->slug) : $product->slug,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'regular_price' => $request->regular_price,
            'sale_price' => $request->sale_price ?: null,
            'sku' => $request->sku,
            'stock_status' => $request->stock_status ?? 'instock',
            'featured' => (bool) $request->featured,
            'quantity' => $request->quantity,
            'image' => $imagePath,
            'category_id' => $request->category_id ?: $product->category_id,
            'brand_id' => $request->brand_id ?: $product->brand_id,
            'gender' => $request->gender ?? $product->gender,
            'is_flash_sale' => (bool) $request->is_flash_sale,
            'flash_sale_price' => $request->flash_sale_price ?: null,
        ]);

        return redirect()->route('admin.products')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function deleteProduct($id)
    {
        Product::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa sản phẩm thành công.');
    }

    // INVENTORY & VARIANTS
    public function inventory()
    {
        $products = Product::with('variants', 'category')->latest()->get();
        return view('admin.inventory', compact('products'));
    }

    public function storeVariant(Request $request, $product_id)
    {
        $request->validate([
            'size' => 'required|string|max:50',
            'color' => 'required|string|max:50',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ], [
            'size.required' => 'Vui lòng nhập kích cỡ (Size).',
            'color.required' => 'Vui lòng nhập màu sắc.',
            'quantity.required' => 'Vui lòng nhập số lượng tồn.',
        ]);

        $product = Product::findOrFail($product_id);
        ProductVariant::create([
            'product_id' => $product->id,
            'size' => $request->size,
            'color' => $request->color,
            'sku' => $request->sku ?: ($product->sku . '-' . $request->size . '-' . substr(md5($request->color), 0, 3)),
            'price' => $request->price ?: $product->effectivePrice(),
            'quantity' => (int) $request->quantity,
        ]);

        return back()->with('success', 'Đã thêm biến thể mới cho sản phẩm ' . $product->name);
    }

    public function deleteVariant($id)
    {
        ProductVariant::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa biến thể.');
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ], [
            'quantity.required' => 'Vui lòng nhập số lượng tồn kho.',
            'quantity.min' => 'Số lượng tồn không thể là số âm.',
        ]);

        $product = Product::findOrFail($id);
        $qty = (int) $request->quantity;
        $product->update([
            'quantity' => $qty,
            'stock_status' => $qty > 0 ? 'instock' : 'outofstock',
        ]);
        return back()->with('success', 'Đã cập nhật tồn kho cho ' . $product->name . ' (' . $qty . ' sản phẩm)');
    }

    // CATEGORIES
    public function categories(Request $request)
    {
        $query = Category::withCount('products');
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        $categories = $query->latest()->get();
        return view('admin.categories', compact('categories'));
    }

    public function addCategory()
    {
        return view('admin.add-category');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.unique' => 'Tên danh mục này đã tồn tại.',
        ]);

        $imagePath = 'frontend/images/women.jpg';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/categories'), $filename);
            $imagePath = 'uploads/categories/' . $filename;
        }

        Category::create([
            'name' => $request->name,
            'slug' => $request->slug ? Str::slug($request->slug) : Str::slug($request->name),
            'image' => $imagePath,
            'gender' => $request->gender ?? 'all',
        ]);

        return redirect()->route('admin.categories')->with('success', 'Đã thêm danh mục mới thành công!');
    }

    public function deleteCategory($id)
    {
        Category::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa danh mục.');
    }

    // BRANDS
    public function brands(Request $request)
    {
        $query = Brand::withCount('products');
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        $brands = $query->latest()->get();
        return view('admin.brands', compact('brands'));
    }

    public function addBrand()
    {
        return view('admin.add-brand');
    }

    public function storeBrand(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:brands,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'name.required' => 'Vui lòng nhập tên thương hiệu.',
            'name.unique' => 'Tên thương hiệu này đã tồn tại.',
        ]);

        $imagePath = 'frontend/images/logo.png';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/brands'), $filename);
            $imagePath = 'uploads/brands/' . $filename;
        }

        Brand::create([
            'name' => $request->name,
            'slug' => $request->slug ? Str::slug($request->slug) : Str::slug($request->name),
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.brands')->with('success', 'Đã thêm thương hiệu mới thành công!');
    }

    public function deleteBrand($id)
    {
        Brand::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa thương hiệu.');
    }

    // ORDERS
    public function orders(Request $request)
    {
        $query = Order::with('items');
        if ($request->filled('name')) {
            $q = $request->name;
            $query->where('order_code', 'like', "%{$q}%")
                  ->orWhere('name', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%");
        }
        $orders = $query->latest()->get();
        return view('admin.orders', compact('orders'));
    }

    public function orderDetails($id)
    {
        $order = Order::with('items.product.category', 'items.product.brand', 'returnRequests')->findOrFail($id);
        $staffUsers = User::whereIn('role', ['staff', 'admin'])->get();
        return view('admin.order-details', compact('order', 'staffUsers'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:ordered,processing,shipping,delivered,canceled',
            'payment_status' => 'nullable|in:pending,paid,refunded',
        ], [
            'status.required' => 'Vui lòng chọn trạng thái đơn hàng.',
        ]);

        $order = Order::findOrFail($id);
        $status = $request->status;

        $data = [
            'status' => $status,
            'payment_status' => $request->payment_status ?? $order->payment_status,
            'shipper_name' => $request->shipper_name ?? $order->shipper_name,
            'shipper_phone' => $request->shipper_phone ?? $order->shipper_phone,
            'tracking_note' => $request->tracking_note ?? $order->tracking_note,
        ];

        if ($status === 'delivered') {
            $data['delivered_date'] = now();
            $data['payment_status'] = 'paid';
        } elseif ($status === 'canceled') {
            $data['canceled_date'] = now();
        }

        $order->update($data);

        OrderNotification::create([
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'title' => 'Cập nhật đơn hàng ' . $order->order_code,
            'message' => 'Trạng thái đơn hàng: ' . strtoupper($status) . ($request->tracking_note ? ' (' . $request->tracking_note . ')' : ''),
            'type' => 'order',
            'for_admin' => false,
        ]);

        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng ' . $order->order_code);
    }

    public function orderTracking(Request $request)
    {
        $order = null;
        if ($request->filled('order_code')) {
            $order = Order::with('items.product')
                ->where('order_code', $request->order_code)
                ->orWhere('id', $request->order_code)
                ->first();
        } else {
            $order = Order::with('items.product')->latest()->first();
        }
        return view('admin.order-tracking', compact('order'));
    }

    // RETURN / EXCHANGE REQUESTS
    public function returns()
    {
        $returns = ReturnRequest::with('order', 'user')->latest()->get();
        return view('admin.returns', compact('returns'));
    }

    public function updateReturn(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,completed,rejected',
        ]);

        $ret = ReturnRequest::with('order')->findOrFail($id);
        $ret->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
        ]);

        OrderNotification::create([
            'user_id' => $ret->user_id,
            'order_id' => $ret->order_id,
            'title' => 'Phản hồi yêu cầu Đổi/Trả đơn ' . ($ret->order->order_code ?? ''),
            'message' => 'Trạng thái: ' . strtoupper($request->status) . ' - Ghi chú: ' . $request->admin_note,
            'type' => 'return',
            'for_admin' => false,
        ]);

        return back()->with('success', 'Đã xử lý phản hồi yêu cầu đổi/trả hàng!');
    }

    // REVIEWS
    public function reviews()
    {
        $reviews = Review::with('product', 'user')->latest()->get();
        return view('admin.reviews', compact('reviews'));
    }

    public function deleteReview($id)
    {
        Review::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa đánh giá.');
    }

    // COUPONS / VOUCHERS
    public function coupons(Request $request)
    {
        $coupons = Coupon::latest()->get();
        return view('admin.coupons', compact('coupons'));
    }

    public function addCoupon()
    {
        return view('admin.add-coupon');
    }

    public function storeCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'cart_value' => 'required|numeric|min:0',
            'expiry_date' => 'required|date',
        ], [
            'code.required' => 'Vui lòng nhập mã giảm giá.',
            'code.unique' => 'Mã giảm giá này đã tồn tại.',
            'value.required' => 'Vui lòng nhập giá trị giảm.',
            'cart_value.required' => 'Vui lòng nhập giá trị đơn hàng tối thiểu.',
            'expiry_date.required' => 'Vui lòng chọn ngày hết hạn.',
        ]);

        Coupon::create([
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'cart_value' => $request->cart_value,
            'expiry_date' => $request->expiry_date,
        ]);

        return redirect()->route('admin.coupons')->with('success', 'Đã tạo mã giảm giá mới thành công!');
    }

    public function deleteCoupon($id)
    {
        Coupon::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa mã giảm giá.');
    }

    // SLIDERS
    public function sliders()
    {
        $sliders = Slider::latest()->get();
        return view('admin.slider', compact('sliders'));
    }

    public function addSlide()
    {
        return view('admin.add-slide');
    }

    public function storeSlide(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề banner.',
        ]);

        $imagePath = 'frontend/images/13.png';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/sliders'), $filename);
            $imagePath = 'uploads/sliders/' . $filename;
        }

        Slider::create([
            'tagline' => $request->tagline ?? 'New Collection',
            'title' => $request->title,
            'subtitle' => $request->subtitle ?? 'Thời trang phong cách 2026',
            'link' => $request->link ?? '/collection',
            'image' => $imagePath,
            'status' => true,
        ]);

        return redirect()->route('admin.sliders')->with('success', 'Đã thêm banner trình chiếu mới!');
    }

    public function deleteSlide($id)
    {
        Slider::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa banner.');
    }

    // USERS & RBAC
    public function users(Request $request)
    {
        $query = User::withCount('orders');
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%')
                  ->orWhere('email', 'like', '%' . $request->name . '%');
        }
        $users = $query->latest()->get();
        return view('admin.users', compact('users'));
    }

    public function updateUserRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:customer,staff,admin',
        ]);

        $user = User::findOrFail($id);
        $user->update(['role' => $request->role]);
        return back()->with('success', 'Đã cập nhật phân quyền cho tài khoản ' . $user->name . ' thành ' . strtoupper($request->role));
    }

    // SALES REPORTS
    public function reports(Request $request)
    {
        $orders = Order::with('items.product')->latest()->get();
        $topProducts = OrderItem::with('product')
            ->selectRaw('product_id, SUM(quantity) as total_qty, SUM(price * quantity) as total_revenue')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->get();

        return view('admin.reports', compact('orders', 'topProducts'));
    }

    // SHIPPER APP
    public function shipperApp()
    {
        $orders = Order::with('items.product')
            ->whereIn('status', ['ordered', 'processing', 'shipping', 'delivered'])
            ->latest()
            ->get();
        return view('admin.shipper-app', compact('orders'));
    }

    public function shipperUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:shipping,delivered,canceled',
        ]);

        $order = Order::findOrFail($id);
        $status = $request->status;
        $order->update([
            'status' => $status,
            'shipper_name' => Auth::check() ? Auth::user()->name : 'Nguyễn Văn Shipper',
            'shipper_phone' => Auth::check() ? Auth::user()->phone : '0902345678',
            'tracking_note' => $request->tracking_note ?: ('Cập nhật từ App Shipper: ' . strtoupper($status)),
            'delivered_date' => $status === 'delivered' ? now() : $order->delivered_date,
            'payment_status' => $status === 'delivered' ? 'paid' : $order->payment_status,
        ]);

        OrderNotification::create([
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'title' => '[Shipper] Đơn ' . $order->order_code . ': ' . strtoupper($status),
            'message' => $order->tracking_note,
            'type' => 'order',
            'for_admin' => false,
        ]);

        return back()->with('success', 'App Shipper đã cập nhật trạng thái đơn ' . $order->order_code);
    }

    // SETTINGS
    public function settings()
    {
        $user = Auth::user() ?? User::where('role', 'admin')->first();
        return view('admin.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user() ?? User::where('role', 'admin')->first();
        if ($user) {
            $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|max:190|unique:users,email,' . $user->id,
                'new_password' => 'nullable|min:6',
            ], [
                'name.required' => 'Vui lòng nhập họ và tên quản trị viên.',
                'email.required' => 'Vui lòng nhập địa chỉ email.',
                'email.unique' => 'Địa chỉ email này đã có người sử dụng.',
                'new_password.min' => 'Mật khẩu mới phải có tối thiểu 6 ký tự.',
            ]);

            $user->update([
                'name' => $request->name,
                'phone' => $request->mobile ?? $user->phone,
                'email' => $request->email,
            ]);
            if ($request->filled('new_password')) {
                $user->update(['password' => Hash::make($request->new_password)]);
            }
        }
        return back()->with('success', 'Đã lưu cài đặt tài khoản quản trị thành công!');
    }
}
