<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Slider;
use App\Models\Coupon;
use App\Models\Contact;
use App\Models\OrderItem;

class StoreController extends Controller
{
    public function index()
    {
        $latestProducts = Product::with('category', 'brand')->latest()->take(6)->get();
        $featuredProducts = Product::with('category', 'brand')->where('featured', true)->take(6)->get();
        $flashSaleProducts = Product::with('category', 'brand')->where('is_flash_sale', true)->take(4)->get();
        $sliders = Slider::where('status', true)->get();
        $coupons = Coupon::where('expiry_date', '>=', now()->format('Y-m-d'))->get();

        // CHỨC NĂNG NHÓM C: Recommendation System & Personalized Recommendation
        $recentIds = session()->get('recently_viewed', []);
        $recentProducts = !empty($recentIds) ? Product::whereIn('id', $recentIds)->take(4)->get() : collect();

        if ($recentProducts->isNotEmpty()) {
            $catIds = $recentProducts->pluck('category_id')->unique();
            $personalizedProducts = Product::whereIn('category_id', $catIds)
                ->whereNotIn('id', $recentIds)
                ->take(4)
                ->get();
            if ($personalizedProducts->isEmpty()) {
                $personalizedProducts = Product::where('featured', true)->take(4)->get();
            }
        } else {
            $personalizedProducts = Product::where('featured', true)->take(4)->get();
        }

        return view('frontend.index', compact(
            'latestProducts',
            'featuredProducts',
            'flashSaleProducts',
            'sliders',
            'coupons',
            'recentProducts',
            'personalizedProducts'
        ));
    }

    public function collection(Request $request)
    {
        $query = Product::with('category', 'brand', 'variants');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%")
                    ->orWhere('short_description', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category')) {
            $cat = Category::where('slug', $request->category)->orWhere('id', $request->category)->first();
            if ($cat) {
                $query->where('category_id', $cat->id);
            }
        }

        if ($request->filled('brand')) {
            $brand = Brand::where('slug', $request->brand)->orWhere('id', $request->brand)->first();
            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('color')) {
            $color = $request->color;
            $query->whereHas('variants', function ($v) use ($color) {
                $v->where('color', 'like', "%{$color}%");
            });
        }

        if ($request->filled('size')) {
            $size = $request->size;
            $query->whereHas('variants', function ($v) use ($size) {
                $v->where('size', $size);
            });
        }

        if ($request->filled('min_price')) {
            $query->where('regular_price', '>=', (float) $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('regular_price', '<=', (float) $request->max_price);
        }

        if ($request->filled('flash_sale')) {
            $query->where('is_flash_sale', true);
        }

        // Sorting
        switch ($request->sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'price_asc':
                $query->orderByRaw('COALESCE(sale_price, regular_price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(sale_price, regular_price) DESC');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(9)->withQueryString();
        $categories = Category::withCount('products')->get();
        $brands = Brand::withCount('products')->get();
        $onSaleProducts = Product::whereNotNull('sale_price')->take(3)->get();
        $featuredProducts = Product::where('featured', true)->take(3)->get();
        $sliders = Slider::where('status', true)->get();

        return view('frontend.collection', compact(
            'products',
            'categories',
            'brands',
            'onSaleProducts',
            'featuredProducts',
            'sliders'
        ));
    }

    public function men()
    {
        $products = Product::where('gender', 'men')->latest()->get();
        $otherProducts = Product::where('featured', true)->take(6)->get();
        $pageTitle = 'THỜI TRANG NAM';
        return view('frontend.gender', compact('products', 'otherProducts', 'pageTitle'));
    }

    public function women()
    {
        $products = Product::where('gender', 'women')->latest()->get();
        $otherProducts = Product::where('featured', true)->take(6)->get();
        $pageTitle = 'THỜI TRANG NỮ';
        return view('frontend.gender', compact('products', 'otherProducts', 'pageTitle'));
    }

    public function flashSale()
    {
        $flashProducts = Product::with('category', 'brand')->where('is_flash_sale', true)->get();
        $coupons = Coupon::where('expiry_date', '>=', now()->format('Y-m-d'))->get();
        return view('frontend.flash-sale', compact('flashProducts', 'coupons'));
    }

    public function single($slug)
    {
        $product = Product::with(['category', 'brand', 'variants', 'reviews.user'])
            ->where('slug', $slug)
            ->orWhere('id', $slug)
            ->firstOrFail();

        // 1. Lưu lịch sử xem sản phẩm vào session (Personalized tracking)
        $recentIds = session()->get('recently_viewed', []);
        if (!in_array($product->id, $recentIds)) {
            array_unshift($recentIds, $product->id);
            $recentIds = array_slice($recentIds, 0, 8);
            session()->put('recently_viewed', $recentIds);
        }

        // 2. CHỨC NĂNG NHÓM C: Recommendation System
        // A. Khách hàng cũng mua cùng nhau (Frequently Bought Together / Collaborative Filtering)
        $orderIds = OrderItem::where('product_id', $product->id)->pluck('order_id');
        $boughtTogether = Product::whereIn('id', function ($q) use ($orderIds, $product) {
            $q->select('product_id')
              ->from('order_items')
              ->whereIn('order_id', $orderIds)
              ->where('product_id', '!=', $product->id);
        })->take(4)->get();

        if ($boughtTogether->count() < 2) {
            $boughtTogether = Product::where('id', '!=', $product->id)
                ->where('category_id', $product->category_id)
                ->take(4)
                ->get();
        }

        // B. Gợi ý cá nhân hóa (Personalized Recommendations)
        $personalizedProducts = Product::where('id', '!=', $product->id)
            ->where('brand_id', $product->brand_id)
            ->take(4)
            ->get();
        if ($personalizedProducts->isEmpty()) {
            $personalizedProducts = Product::where('id', '!=', $product->id)->take(4)->get();
        }

        $categories = Category::withCount('products')->get();
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('frontend.single', compact(
            'product',
            'categories',
            'relatedProducts',
            'boughtTogether',
            'personalizedProducts'
        ));
    }

    public function articles()
    {
        return view('frontend.articles');
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'userName' => 'required|string|max:100',
            'userEmail' => 'required|email|max:150',
            'userMsg' => 'required|string|max:1000',
        ], [
            'userName.required' => 'Vui lòng nhập họ và tên của bạn.',
            'userEmail.required' => 'Vui lòng cung cấp địa chỉ email liên hệ.',
            'userEmail.email' => 'Địa chỉ email không đúng định dạng.',
            'userMsg.required' => 'Vui lòng nhập nội dung tin nhắn hoặc góp ý.',
        ]);

        Contact::create([
            'name' => $request->userName,
            'email' => $request->userEmail,
            'subject' => $request->subject ?? 'Liên hệ góp ý từ khách hàng',
            'message' => $request->userMsg,
        ]);

        return back()->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi đã tiếp nhận thông tin và sẽ phản hồi sớm nhất.');
    }

    public function privacy()
    {
        return view('frontend.privacy');
    }
}
