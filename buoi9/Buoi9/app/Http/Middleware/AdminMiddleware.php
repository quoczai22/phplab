<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Xác minh người dùng hiện tại có phải ADMIN hay không.
     *
     * - Chưa đăng nhập      -> chuyển về trang đăng nhập admin
     * - Đã đăng nhập (user) -> báo lỗi 403 (không có quyền)
     * - Là admin            -> cho đi tiếp
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Chưa đăng nhập
        if (!Auth::check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Vui lòng đăng nhập bằng tài khoản admin.');
        }

        /** @var User|null $user */
        $user = Auth::user();

        // 2. Đã đăng nhập nhưng không phải admin
        if (!$user || !$user->isAdmin()) {
            abort(403, 'Bạn không có quyền truy cập vào trang này.');
        }

        // 3. Là admin
        return $next($request);
    }
}
