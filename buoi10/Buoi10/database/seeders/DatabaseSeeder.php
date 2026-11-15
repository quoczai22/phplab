<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Coupon;
use App\Models\Slider;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\ReturnRequest;
use App\Models\OrderNotification;
use App\Models\Wishlist;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Users (Admin, Staff, Customer)
        $admin = User::create([
            'name' => 'Trịnh Hữu Kiến Quốc (Admin)',
            'email' => 'admin@fashionstore.com',
            'phone' => '0901234567',
            'role' => 'admin',
            'avatar' => 'admin/images/avatar/user-1.png',
            'password' => Hash::make('123456'),
        ]);

        $staff = User::create([
            'name' => 'Nguyễn Văn Nhân Viên',
            'email' => 'staff@fashionstore.com',
            'phone' => '0902345678',
            'role' => 'staff',
            'avatar' => 'admin/images/avatar/user-1.png',
            'password' => Hash::make('123456'),
        ]);

        $customer = User::create([
            'name' => 'Lê Thị Khách Hàng',
            'email' => 'customer@fashionstore.com',
            'phone' => '0987654321',
            'role' => 'customer',
            'avatar' => 'admin/images/avatar/user-1.png',
            'password' => Hash::make('123456'),
        ]);

        // 2. Categories
        $categoriesData = [
            ['name' => 'Thời Trang Nam', 'slug' => 'thoi-trang-nam', 'image' => 'frontend/images/men.jpg', 'gender' => 'men'],
            ['name' => 'Thời Trang Nữ', 'slug' => 'thoi-trang-nu', 'image' => 'frontend/images/women.jpg', 'gender' => 'women'],
            ['name' => 'Đầm & Váy Dự Tiệc', 'slug' => 'dam-vay-du-tiec', 'image' => 'frontend/images/pi.jpg', 'gender' => 'women'],
            ['name' => 'Áo Khoác & Denim', 'slug' => 'ao-khoac-denim', 'image' => 'frontend/images/men1.jpg', 'gender' => 'all'],
            ['name' => 'Áo Thun & Tanktop', 'slug' => 'ao-thun-tanktop', 'image' => 'frontend/images/po.jpg', 'gender' => 'all'],
            ['name' => 'Bộ Sưu Tập Mùa Hè', 'slug' => 'bo-suu-tap-mua-he', 'image' => 'frontend/images/pi4.jpg', 'gender' => 'all'],
        ];
        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[] = Category::create($c);
        }

        // 3. Brands
        $brandsData = [
            ['name' => 'Zara Studio', 'slug' => 'zara-studio', 'image' => 'frontend/images/logo.png'],
            ['name' => 'Gucci Luxury', 'slug' => 'gucci-luxury', 'image' => 'frontend/images/logo.png'],
            ['name' => 'H&M Modern', 'slug' => 'hm-modern', 'image' => 'frontend/images/logo.png'],
            ['name' => 'Uniqlo LifeWear', 'slug' => 'uniqlo-lifewear', 'image' => 'frontend/images/logo.png'],
            ['name' => 'Levi\'s Denim', 'slug' => 'levis-denim', 'image' => 'frontend/images/logo.png'],
        ];
        $brands = [];
        foreach ($brandsData as $b) {
            $brands[] = Brand::create($b);
        }

        // 4. Products
        $productsData = [
            [
                'name' => 'Premium Denim Women\'s Hidden Jacket',
                'slug' => 'premium-denim-womens-hidden-jacket',
                'short_description' => 'Áo khoác Denim cao cấp thiết kế trẻ trung, chất liệu cotton thoáng mát.',
                'description' => 'Sản phẩm áo khoác Denim nữ cao cấp nằm trong bộ sưu tập mới nhất. Chất vải mềm mại, giữ form tốt, dễ dàng phối cùng quần jeans hoặc chân váy.',
                'regular_price' => 210000,
                'sale_price' => 179000,
                'sku' => 'DNM-W001',
                'stock_status' => 'instock',
                'featured' => true,
                'quantity' => 45,
                'image' => 'frontend/images/pi.jpg',
                'images' => 'frontend/images/sin1.jpg,frontend/images/sin2.jpg,frontend/images/sin3.jpg,frontend/images/sin4.jpg',
                'category_id' => $categories[1]->id,
                'brand_id' => $brands[4]->id,
                'gender' => 'women',
                'is_flash_sale' => true,
                'flash_sale_price' => 149000,
                'flash_sale_end' => now()->addDays(5),
            ],
            [
                'name' => 'Summer Floral Silk Dress',
                'slug' => 'summer-floral-silk-dress',
                'short_description' => 'Đầm lụa họa tiết mùa hè thanh lịch, tôn dáng.',
                'description' => 'Thiết kế đầm lụa cao cấp mang phong cách châu Âu hiện đại, phù hợp đi dạo phố, công sở và dự tiệc nhẹ.',
                'regular_price' => 199000,
                'sale_price' => 169000,
                'sku' => 'DRS-W002',
                'stock_status' => 'instock',
                'featured' => true,
                'quantity' => 30,
                'image' => 'frontend/images/pi4.jpg',
                'images' => 'frontend/images/sin2.jpg,frontend/images/sin1.jpg,frontend/images/sin3.jpg',
                'category_id' => $categories[2]->id,
                'brand_id' => $brands[0]->id,
                'gender' => 'women',
                'is_flash_sale' => true,
                'flash_sale_price' => 139000,
                'flash_sale_end' => now()->addDays(5),
            ],
            [
                'name' => 'Elegant Evening Chic Blouse',
                'slug' => 'elegant-evening-chic-blouse',
                'short_description' => 'Áo kiểu nữ sang trọng phối chân váy công sở.',
                'description' => 'Chất liệu voan lụa mềm mịn, thoáng khí, đường may tỉ mỉ sắc nét.',
                'regular_price' => 150000,
                'sale_price' => 125000,
                'sku' => 'BLS-W003',
                'stock_status' => 'instock',
                'featured' => true,
                'quantity' => 28,
                'image' => 'frontend/images/pi5.jpg',
                'images' => 'frontend/images/sin3.jpg,frontend/images/sin1.jpg',
                'category_id' => $categories[1]->id,
                'brand_id' => $brands[1]->id,
                'gender' => 'women',
                'is_flash_sale' => false,
                'flash_sale_price' => null,
                'flash_sale_end' => null,
            ],
            [
                'name' => 'Modern Streetwear Kurti Top',
                'slug' => 'modern-streetwear-kurti-top',
                'short_description' => 'Áo khoác nhẹ nữ phong cách Streetwear năng động.',
                'description' => 'Thiết kế cá tính, màu sắc trẻ trung, phù hợp cho các buổi dạo phố hoặc du lịch.',
                'regular_price' => 185000,
                'sale_price' => 155000,
                'sku' => 'STR-W004',
                'stock_status' => 'instock',
                'featured' => false,
                'quantity' => 50,
                'image' => 'frontend/images/pi6.jpg',
                'images' => 'frontend/images/sin4.jpg,frontend/images/sin2.jpg',
                'category_id' => $categories[5]->id,
                'brand_id' => $brands[2]->id,
                'gender' => 'women',
                'is_flash_sale' => true,
                'flash_sale_price' => 119000,
                'flash_sale_end' => now()->addDays(3),
            ],
            [
                'name' => 'Men\'s Classic Oxford Blazer',
                'slug' => 'mens-classic-oxford-blazer',
                'short_description' => 'Áo vest/blazer nam lịch lãm phong cách quý ông.',
                'description' => 'Form dáng Slim-fit tôn dáng, chất vải cao cấp chống nhăn, thích hợp cho sự kiện và công sở.',
                'regular_price' => 250000,
                'sale_price' => 199000,
                'sku' => 'MEN-B005',
                'stock_status' => 'instock',
                'featured' => true,
                'quantity' => 35,
                'image' => 'frontend/images/men.jpg',
                'images' => 'frontend/images/sin1.jpg,frontend/images/sin2.jpg',
                'category_id' => $categories[0]->id,
                'brand_id' => $brands[1]->id,
                'gender' => 'men',
                'is_flash_sale' => true,
                'flash_sale_price' => 165000,
                'flash_sale_end' => now()->addDays(4),
            ],
            [
                'name' => 'Men\'s Urban Casual Shirt',
                'slug' => 'mens-urban-casual-shirt',
                'short_description' => 'Áo sơ mi nam dài tay chất cotton thấm hút mồ hôi.',
                'description' => 'Sơ mi nam thiết kế tối giản, lịch sự, dễ phối với quần âu hoặc jeans.',
                'regular_price' => 140000,
                'sale_price' => 119000,
                'sku' => 'MEN-S006',
                'stock_status' => 'instock',
                'featured' => true,
                'quantity' => 60,
                'image' => 'frontend/images/men1.jpg',
                'images' => 'frontend/images/sin2.jpg,frontend/images/sin3.jpg',
                'category_id' => $categories[0]->id,
                'brand_id' => $brands[3]->id,
                'gender' => 'men',
                'is_flash_sale' => false,
                'flash_sale_price' => null,
                'flash_sale_end' => null,
            ],
            [
                'name' => 'Men\'s Winter Trench Coat',
                'slug' => 'mens-winter-trench-coat',
                'short_description' => 'Áo khoác măng tô nam giữ ấm cao cấp.',
                'description' => 'Chất liệu dạ pha len cao cấp, giữ nhiệt tốt, đường cắt may chuẩn form quốc tế.',
                'regular_price' => 290000,
                'sale_price' => 239000,
                'sku' => 'MEN-C007',
                'stock_status' => 'instock',
                'featured' => false,
                'quantity' => 20,
                'image' => 'frontend/images/men2.jpg',
                'images' => 'frontend/images/sin3.jpg,frontend/images/sin4.jpg',
                'category_id' => $categories[3]->id,
                'brand_id' => $brands[0]->id,
                'gender' => 'men',
                'is_flash_sale' => false,
                'flash_sale_price' => null,
                'flash_sale_end' => null,
            ],
            [
                'name' => 'Feel Tank Sporty Top',
                'slug' => 'feel-tank-sporty-top',
                'short_description' => 'Áo tanktop thể thao co giãn 4 chiều.',
                'description' => 'Phù hợp tập gym, yoga hoặc mặc dạo phố mùa hè.',
                'regular_price' => 75000,
                'sale_price' => 59950,
                'sku' => 'TNK-008',
                'stock_status' => 'instock',
                'featured' => true,
                'quantity' => 80,
                'image' => 'frontend/images/po.jpg',
                'images' => 'frontend/images/sin1.jpg,frontend/images/sin4.jpg',
                'category_id' => $categories[4]->id,
                'brand_id' => $brands[2]->id,
                'gender' => 'women',
                'is_flash_sale' => false,
                'flash_sale_price' => null,
                'flash_sale_end' => null,
            ],
            [
                'name' => 'Pintuck Designer Tank',
                'slug' => 'pintuck-designer-tank',
                'short_description' => 'Áo thun thiết kế trẻ trung năng động.',
                'description' => 'Chất liệu 100% Organic Cotton thân thiện với làn da.',
                'regular_price' => 65000,
                'sale_price' => 45550,
                'sku' => 'TNK-009',
                'stock_status' => 'instock',
                'featured' => false,
                'quantity' => 40,
                'image' => 'frontend/images/po5.jpg',
                'images' => 'frontend/images/sin2.jpg',
                'category_id' => $categories[4]->id,
                'brand_id' => $brands[3]->id,
                'gender' => 'women',
                'is_flash_sale' => false,
                'flash_sale_price' => null,
                'flash_sale_end' => null,
            ],
            [
                'name' => 'Boho Chic Summer Tunic',
                'slug' => 'boho-chic-summer-tunic',
                'short_description' => 'Áo tunic họa tiết Boho phóng khoáng.',
                'description' => 'Thiết kế độc đáo cho mùa lễ hội và kỳ nghỉ biển.',
                'regular_price' => 89000,
                'sale_price' => 59950,
                'sku' => 'BOH-010',
                'stock_status' => 'instock',
                'featured' => false,
                'quantity' => 25,
                'image' => 'frontend/images/po1.jpg',
                'images' => 'frontend/images/sin3.jpg',
                'category_id' => $categories[5]->id,
                'brand_id' => $brands[0]->id,
                'gender' => 'women',
                'is_flash_sale' => false,
                'flash_sale_price' => null,
                'flash_sale_end' => null,
            ],
            [
                'name' => 'Vintage Indigo Jeans Shirt',
                'slug' => 'vintage-indigo-jeans-shirt',
                'short_description' => 'Áo sơ mi牛仔/Denim phong cách cổ điển.',
                'description' => 'Màu chàm tự nhiên, càng mặc càng đẹp.',
                'regular_price' => 95000,
                'sale_price' => 69950,
                'sku' => 'DNM-011',
                'stock_status' => 'instock',
                'featured' => true,
                'quantity' => 18,
                'image' => 'frontend/images/po2.jpg',
                'images' => 'frontend/images/sin4.jpg',
                'category_id' => $categories[3]->id,
                'brand_id' => $brands[4]->id,
                'gender' => 'women',
                'is_flash_sale' => false,
                'flash_sale_price' => null,
                'flash_sale_end' => null,
            ],
            [
                'name' => 'Royal Silk Evening Gown',
                'slug' => 'royal-silk-evening-gown',
                'short_description' => 'Đầm dạ hội lụa cao cấp sang trọng.',
                'description' => 'Tôn vinh vẻ đẹp quý phái trong mọi bữa tiệc.',
                'regular_price' => 260000,
                'sale_price' => 199000,
                'sku' => 'GWN-012',
                'stock_status' => 'instock',
                'featured' => true,
                'quantity' => 12,
                'image' => 'frontend/images/po3.jpg',
                'images' => 'frontend/images/sin1.jpg',
                'category_id' => $categories[2]->id,
                'brand_id' => $brands[1]->id,
                'gender' => 'women',
                'is_flash_sale' => false,
                'flash_sale_price' => null,
                'flash_sale_end' => null,
            ],
        ];

        $products = [];
        $sizes = ['S', 'M', 'L', 'XL'];
        $colors = ['Đen', 'Trắng', 'Xanh Navy', 'Đỏ Đô'];

        foreach ($productsData as $p) {
            $prod = Product::create($p);
            $products[] = $prod;

            // Create variants for each product (Group B: Quản lý biến thể sản phẩm & tồn kho)
            foreach (['S', 'M', 'L', 'XL'] as $idx => $sz) {
                ProductVariant::create([
                    'product_id' => $prod->id,
                    'size' => $sz,
                    'color' => $colors[$idx % count($colors)],
                    'sku' => $prod->sku . '-' . $sz,
                    'price' => $prod->effectivePrice(),
                    'quantity' => rand(5, 20),
                ]);
            }
        }

        // 5. Coupons / Vouchers (Group B)
        Coupon::create([
            'code' => 'OFF10',
            'type' => 'percent',
            'value' => 10,
            'cart_value' => 100000,
            'expiry_date' => now()->addMonths(3)->format('Y-m-d'),
        ]);
        Coupon::create([
            'code' => 'SUMMER20',
            'type' => 'percent',
            'value' => 20,
            'cart_value' => 200000,
            'expiry_date' => now()->addMonths(2)->format('Y-m-d'),
        ]);
        Coupon::create([
            'code' => 'GIAM50',
            'type' => 'fixed',
            'value' => 50000,
            'cart_value' => 150000,
            'expiry_date' => now()->addMonths(6)->format('Y-m-d'),
        ]);

        // 6. Sliders
        Slider::create([
            'tagline' => 'Collection',
            'title' => 'Summer 2026',
            'subtitle' => 'Helping you look cool - Sale up to 40% Off',
            'link' => '/collection',
            'image' => 'frontend/images/13.png',
            'status' => true,
        ]);
        Slider::create([
            'tagline' => 'New Arrivals',
            'title' => 'Night Spring',
            'subtitle' => 'Luxury Dresses & Streetwear',
            'link' => '/women',
            'image' => 'frontend/images/13.png',
            'status' => true,
        ]);

        // 7. Customer Address
        Address::create([
            'user_id' => $customer->id,
            'name' => 'Lê Thị Khách Hàng',
            'phone' => '0987654321',
            'address' => '140 Lê Trọng Tấn, Phường Tây Thạnh',
            'district' => 'Quận Tân Phú',
            'city' => 'TP. Hồ Chí Minh',
            'ward' => 'Phường Tây Thạnh',
            'is_default' => true,
        ]);

        // 8. Sample Orders
        $order1 = Order::create([
            'order_code' => 'ORD-2026001',
            'user_id' => $customer->id,
            'subtotal' => 318000,
            'discount' => 31800,
            'tax' => 28620,
            'total' => 314820,
            'name' => 'Lê Thị Khách Hàng',
            'phone' => '0987654321',
            'address' => '140 Lê Trọng Tấn, Quận Tân Phú',
            'city' => 'TP. Hồ Chí Minh',
            'payment_mode' => 'vnpay',
            'payment_status' => 'paid',
            'status' => 'delivered',
            'shipper_name' => 'Nguyễn Văn Nhân Viên',
            'shipper_phone' => '0902345678',
            'tracking_note' => 'Giao hàng thành công tại sảnh chính',
            'delivered_date' => now()->subDays(2),
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $products[0]->id,
            'price' => 149000,
            'quantity' => 1,
            'options' => 'Size: M | Màu: Đen',
        ]);
        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $products[1]->id,
            'price' => 169000,
            'quantity' => 1,
            'options' => 'Size: S | Màu: Trắng',
        ]);

        $order2 = Order::create([
            'order_code' => 'ORD-2026002',
            'user_id' => $customer->id,
            'subtotal' => 199000,
            'discount' => 0,
            'tax' => 19900,
            'total' => 218900,
            'name' => 'Lê Thị Khách Hàng',
            'phone' => '0987654321',
            'address' => '140 Lê Trọng Tấn, Quận Tân Phú',
            'city' => 'TP. Hồ Chí Minh',
            'payment_mode' => 'cod',
            'payment_status' => 'pending',
            'status' => 'shipping',
            'shipper_name' => 'Nguyễn Văn Nhân Viên',
            'shipper_phone' => '0902345678',
            'tracking_note' => 'Shipper đang trên đường giao đến Quận Tân Phú',
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $products[4]->id,
            'price' => 199000,
            'quantity' => 1,
            'options' => 'Size: L | Màu: Xanh Navy',
        ]);

        $order3 = Order::create([
            'order_code' => 'ORD-2026003',
            'user_id' => $customer->id,
            'subtotal' => 125000,
            'discount' => 0,
            'tax' => 12500,
            'total' => 137500,
            'name' => 'Trần Minh Quân',
            'phone' => '0912345678',
            'address' => '25 Nguyễn Huệ, Quận 1',
            'city' => 'TP. Hồ Chí Minh',
            'payment_mode' => 'momo',
            'payment_status' => 'paid',
            'status' => 'ordered',
            'tracking_note' => 'Đơn hàng mới tạo, đang chờ xác nhận',
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $products[2]->id,
            'price' => 125000,
            'quantity' => 1,
            'options' => 'Size: M | Màu: Trắng',
        ]);

        // 9. Sample Return Request (Group B: Đổi/trả hàng)
        ReturnRequest::create([
            'order_id' => $order1->id,
            'user_id' => $customer->id,
            'type' => 'exchange',
            'reason' => 'Muốn đổi từ Size S sang Size M cho vừa vặn hơn.',
            'status' => 'pending',
            'admin_note' => 'Đang kiểm tra tồn kho Size M',
        ]);

        // 10. Sample Reviews (Group B: Đánh giá sản phẩm)
        Review::create([
            'user_id' => $customer->id,
            'product_id' => $products[0]->id,
            'rating' => 5,
            'comment' => 'Áo khoác Denim cực đẹp, chất vải dày dặn, giao hàng nhanh!',
            'status' => true,
        ]);
        Review::create([
            'user_id' => $admin->id,
            'product_id' => $products[0]->id,
            'rating' => 4,
            'comment' => 'Form chuẩn, màu sắc giống hình 100%.',
            'status' => true,
        ]);
        Review::create([
            'user_id' => $customer->id,
            'product_id' => $products[1]->id,
            'rating' => 5,
            'comment' => 'Đầm mặc rất mát và sang, sẽ ủng hộ shop tiếp!',
            'status' => true,
        ]);

        // 11. Sample Wishlist
        Wishlist::create([
            'user_id' => $customer->id,
            'product_id' => $products[0]->id,
        ]);
        Wishlist::create([
            'user_id' => $customer->id,
            'product_id' => $products[4]->id,
        ]);

        // 12. Sample Notifications
        OrderNotification::create([
            'user_id' => $customer->id,
            'order_id' => $order2->id,
            'title' => 'Đơn hàng ORD-2026002 đang được giao',
            'message' => 'Shipper Nguyễn Văn Nhân Viên (0902345678) đang giao đơn hàng của bạn.',
            'type' => 'order',
            'for_admin' => false,
        ]);
        OrderNotification::create([
            'user_id' => $customer->id,
            'order_id' => $order3->id,
            'title' => 'Đơn hàng mới: ORD-2026003',
            'message' => 'Khách hàng Trần Minh Quân vừa đặt đơn hàng trị giá 137.500 đ',
            'type' => 'order',
            'for_admin' => true,
        ]);
    }
}
