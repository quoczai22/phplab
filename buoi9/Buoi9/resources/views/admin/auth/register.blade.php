@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Đăng ký tài khoản</h3>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label>Họ tên</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}">
            <small class="text-danger" id="nameError">{{ $errors->first('name') }}</small>
        </div>

        <div class="mb-3">
            <label>Số điện thoại</label>
            <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}">
            <small class="text-danger" id="phoneError">{{ $errors->first('phone') }}</small>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="text" id="email" name="email" class="form-control" value="{{ old('email') }}">
            <small class="text-danger" id="emailError">{{ $errors->first('email') }}</small>
        </div>

        <div class="mb-3">
            <label>Mật khẩu</label>
            <input type="password" id="password" name="password" class="form-control">
            <small class="text-danger" id="passwordError">{{ $errors->first('password') }}</small>
        </div>

        <div class="mb-3">
            <label>Xác nhận mật khẩu</label>
            <input type="password" id="confirm" name="password_confirmation" class="form-control">
            <small class="text-danger" id="confirmError"></small>
        </div>

        <button type="submit" class="btn btn-primary">Đăng ký</button>
    </form>
</div>

<script>
    const nameInput = document.getElementById('name');
    const phoneInput = document.getElementById('phone');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('confirm');

    // Kiểm tra tên realtime
    nameInput.addEventListener('input', function () {
        let v = this.value;
        let error = "";
        if (v.length < 3) {
            error = "Tên phải >= 3 ký tự";
        } else if (v.trim() !== v) {
            error = "Không được có khoảng trắng đầu/cuối";
        } else if (v.includes("  ")) {
            error = "Không được có 2 khoảng trắng liên tiếp";
        }
        document.getElementById('nameError').innerText = error;
    });

    // Kiểm tra SĐT realtime
    phoneInput.addEventListener('input', function () {
        let v = this.value;
        let error = "";
        if (!(v.startsWith("0") && v.length === 10) && 
            !(v.startsWith("+84") && v.length === 12) && 
            !(v.startsWith("84") && v.length === 11)) {
            error = "SĐT không hợp lệ";
        }
        document.getElementById('phoneError').innerText = error;
    });

    // Kiểm tra Email realtime
    emailInput.addEventListener('input', function () {
        let v = this.value;
        let error = "";
        if (!v.endsWith("@gmail.com")) {
            error = "Chỉ chấp nhận gmail.com";
        } else {
            let namePart = v.split("@")[0];
            if (namePart.length < 6 || namePart.length > 30) {
                error = "Tên email phải từ 6-30 ký tự";
            }
        }
        document.getElementById('emailError').innerText = error;
    });

    // Kiểm tra Password realtime
    passwordInput.addEventListener('input', function () {
        let v = this.value;
        let error = "";
        let hasUpper = /[A-Z]/.test(v);
        let hasLower = /[a-z]/.test(v);
        let hasNumber = /[0-9]/.test(v);
        let hasSpecial = /[!@#$%^&*]/.test(v);

        if (v.length < 8) error = "Tối thiểu 8 ký tự";
        else if (!hasUpper) error = "Phải có chữ hoa";
        else if (!hasLower) error = "Phải có chữ thường";
        else if (!hasNumber) error = "Phải có số";
        else if (!hasSpecial) error = "Phải có ký tự đặc biệt (!@#$%^&*)";

        document.getElementById('passwordError').innerText = error;
    });

    // Kiểm tra xác nhận mật khẩu
    confirmInput.addEventListener('input', function () {
        let error = "";
        if (this.value !== passwordInput.value) {
            error = "Mật khẩu không khớp";
        }
        document.getElementById('confirmError').innerText = error;
    });
</script>
@endsection