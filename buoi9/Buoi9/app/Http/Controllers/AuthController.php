<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showRegister(){
        return view('auth.register');
    }

    public function register(Request $request){
        $request->validate([
            'name'=>['required','min:3','string'],
            'phone'=>['required','unique:users,phone',
                'regex:/^(0\d{9}|\+84\d{9}|84\d{9})$/'],
            'email'=>['required','unique:users,email','ends_with:@gmail.com'],
            'password'=>['required','confirmed',Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()],
        ],
    [
        'phone.regex' => 'SDT không hợp lệ. Vui lòng nhập đúng định dạng số điện thoại Việt Nam.',
        'phone.unique' => 'Số điện thoại đã tồn tại. Vui lòng sử dụng số điện thoại khác.',
        'email.regex' => 'Email không hợp lệ. Vui lòng nhập đúng định dạng email.',
        'email.unique'=>'Email đã tồn tại. Vui lòng sử dụng email khác.',
        'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
    ]
    );

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_USER,
        ]);


        return redirect()->route('login')->with('success', 'Đăng ký thành công. Vui lòng đăng nhập để tiếp tục.');
    }

    public function showLogin(Request $request){
        return view('auth.login');
    }

    public function login(Request $request){
        $request->validate([
            'login'=>'required',// 'login' có thể là email hoặc số điện thoại
            'password'=>['required'],
        ],
        [
            'login.required'=>'Vui lòng nhập email hoặc số điện thoại.',
            'password.required'=>'Vui lòng nhập mật khẩu.',
        ]);

        $login = $request->login;
        $password = $request->password;

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        // Attempt login
        if (Auth::attempt([$field => $login, 'password' => $password])) {
            $request->session()->regenerate();

            /** @var User $user */
            $user = Auth::user();
            if ($user && $user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended('/');
        }

        return redirect()->back()
        ->with('error', 'Sai tài khoản hoặc mật khẩu')
        ->withInput($request->only('login'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
    
}