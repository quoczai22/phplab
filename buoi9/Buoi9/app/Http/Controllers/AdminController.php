<?php

namespace App\Http\Controllers;

use App\Models\Bicycle;
use App\Models\Station;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // vào /admin (đã được bảo vệ bởi middleware 'admin' trong routes/web.php)
    public function index()
    {
        return view('admin.dashboard', [
            'totalStations' => Station::count(),
            'totalBicycles' => Bicycle::count(),
            'totalUsers'    => User::where('role', User::ROLE_USER)->count(),
        ]);
    }

    // form login
    public function showLogin()
    {
        /** @var User|null $user */
        $user = Auth::user();

        // Nếu đã đăng nhập bằng admin rồi thì vào thẳng dashboard
        if ($user && $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    // xử lý login: đăng nhập bằng tài khoản trong DB và kiểm tra role = admin
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required', // email hoặc số điện thoại
            'password' => 'required'
        ], [
            'username.required' => 'Vui lòng nhập email hoặc số điện thoại.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $field = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (!Auth::attempt([$field => $request->username, 'password' => $request->password])) {
            return back()
                ->with('error', 'Sai tài khoản hoặc mật khẩu')
                ->withInput($request->only('username'));
        }

        /** @var User|null $user */
        $user = Auth::user();

        // Đăng nhập đúng nhưng KHÔNG phải admin -> đăng xuất ngay
        if (!$user || !$user->isAdmin()) {
            Auth::logout();
            return back()
                ->with('error', 'Tài khoản này không có quyền quản trị.')
                ->withInput($request->only('username'));
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    // logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
