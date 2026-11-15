<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\OrderNotification;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('frontend.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Vui lòng nhập email hoặc tên đăng nhập.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        // Cho phép đăng nhập bằng email hoặc tên tài khoản
        $loginField = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (Auth::attempt([$loginField => $request->email, 'password' => $request->password], $request->filled('remember'))) {
            $request->session()->regenerate();
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập trang quản trị thành công!');
            }
            return redirect()->intended(route('home'))->with('success', 'Đăng nhập tài khoản thành công!');
        }

        return back()->with('error', 'Địa chỉ email hoặc mật khẩu không chính xác.')->withInput();
    }

    public function showRegister()
    {
        return view('frontend.signup');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'required|email|max:190|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|min:6|confirmed',
        ], [
            'first_name.required' => 'Vui lòng nhập họ và tên đệm.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.unique' => 'Địa chỉ email này đã được đăng ký tài khoản.',
            'phone.required' => 'Vui lòng nhập số điện thoại liên hệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có độ dài tối thiểu 6 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        $fullName = trim($request->first_name . ' ' . $request->last_name);

        $user = User::create([
            'name' => $fullName,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => 'customer',
            'avatar' => 'admin/images/avatar/user-1.png',
            'password' => Hash::make($request->password),
        ]);

        OrderNotification::create([
            'user_id' => $user->id,
            'title' => 'Chào mừng thành viên mới!',
            'message' => 'Tài khoản ' . $user->name . ' đã kích hoạt thành công. Nhập mã OFF10 để giảm 10% đơn hàng đầu tiên!',
            'type' => 'account',
            'for_admin' => false,
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Chúc mừng bạn đã đăng ký tài khoản thành công!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Đã đăng xuất tài khoản an toàn.');
    }
}
