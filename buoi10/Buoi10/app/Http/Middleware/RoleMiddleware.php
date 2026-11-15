<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để truy cập trang quản trị.');
        }

        $user = Auth::user();
        if (!empty($roles) && !in_array($user->role, $roles)) {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập khu vực này (Yêu cầu quyền: ' . implode(', ', $roles) . ').');
        }

        return $next($request);
    }
}

