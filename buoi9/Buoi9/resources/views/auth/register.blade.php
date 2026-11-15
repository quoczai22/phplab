@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="mb-3">
        <label>Họ tên</label>
        <input type="text" id="name" name="name" class="form-control"
            value="{{ old('name') }}">
        <small class="text-danger" id="nameError">
            {{ $errors->first('name') }}
        </small>
    </div>

    <div class="mb-3">
        <label>Số điện thoại</label>
        <input type="text" id="phone" name="phone" class="form-control"
            value="{{ old('phone') }}">
        <small class="text-danger" id="phoneError">
            {{ $errors->first('phone') }}
        </small>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="text" id="email" name="email" class="form-control"
            value="{{ old('email') }}">
        <small class="text-danger" id="emailError">
            {{ $errors->first('email') }}
        </small>
    </div>

    <div class="mb-3">
        <label>Mật khẩu</label>
        <input type="password" id="password" name="password" class="form-control">
        <small class="text-danger" id="passwordError">
            {{ $errors->first('password') }}
        </small>
    </div>

    <div class="mb-3">
        <label>Xác nhận mật khẩu</label>
        <input type="password" id="confirm" name="password_confirmation" class="form-control">
        <small class="text-danger" id="confirmError"></small>
    </div>

    <button class="btn btn-primary">Đăng ký</button>
</form>

<script>
const nameInput = document.getElementById('name');
const phoneInput = document.getElementById('phone');
const emailInput = document.getElementById('email');
const passwordInput = document.getElementById('password');
const confirmInput = document.getElementById('confirm');

// ===== NAME =====
nameInput.addEventListener('input', function () {
    let v = this.value;
    let error = "";

    if (v.length < 3) {
        error = "Tên phải >= 3 ký tự";
    }
    else if (v.trim() !== v) {
        error = "Không được có khoảng trắng đầu/cuối";
    }
    else if (v.includes("  ")) {
        error = "Không được có 2 khoảng trắng liên tiếp";
    }
    else {
        for (let c of v) {
            if (c !== " " && !(c.toLowerCase() !== c.toUpperCase())) {
                error = "Chỉ được nhập chữ và khoảng trắng";
                break;
            }
        }
    }

    document.getElementById('nameError').innerText = error;
});

// ===== PHONE =====
phoneInput.addEventListener('input', function () {
    let v = this.value;
    let error = "";

    if (!(v.startsWith("0") && v.length === 10) &&
        !(v.startsWith("+84") && v.length === 12) &&
        !(v.startsWith("84") && v.length === 11)) {
        error = "SĐT không hợp lệ";
    }
    else {
        for (let c of v.replace("+", "")) {
            if (c < '0' || c > '9') {
                error = "SĐT chỉ được chứa số";
                break;
            }
        }
    }

    document.getElementById('phoneError').innerText = error;
});

// ===== EMAIL =====
emailInput.addEventListener('input', function () {
    let v = this.value;
    let error = "";

    if (!v.endsWith("@gmail.com")) {
        error = "Chỉ chấp nhận gmail.com";
    }
    else {
        let name = v.split("@")[0];

        if (name.length < 6 || name.length > 30) {
            error = "Tên email phải 6-30 ký tự";
        }
        else if (name.startsWith(".") || name.endsWith(".")) {
            error = "Không được bắt đầu/kết thúc bằng dấu chấm";
        }
        else if (name.includes("..")) {
            error = "Không được chứa 2 dấu chấm liên tiếp";
        }
        else {
            for (let c of name) {
                if (!(
                    (c >= 'a' && c <= 'z') ||
                    (c >= '0' && c <= '9') ||
                    c === "."
                )) {
                    error = "Chỉ cho phép a-z, 0-9 và dấu chấm";
                    break;
                }
            }
        }
    }

    document.getElementById('emailError').innerText = error;
});

// ===== PASSWORD =====
passwordInput.addEventListener('input', function () {
    let v = this.value;
    let error = "";

    let hasUpper = false;
    let hasLower = false;
    let hasNumber = false;
    let hasSpecial = false;

    const special = "!@#$%^&*";

    for (let c of v) {
        if (c >= 'A' && c <= 'Z') hasUpper = true;
        else if (c >= 'a' && c <= 'z') hasLower = true;
        else if (c >= '0' && c <= '9') hasNumber = true;
        else if (special.includes(c)) hasSpecial = true;
        else {
            error = "Ký tự không hợp lệ";
            break;
        }
    }

    if (!error) {
        if (v.length < 8) error = "Tối thiểu 8 ký tự";
        else if (!hasUpper) error = "Phải có chữ hoa";
        else if (!hasLower) error = "Phải có chữ thường";
        else if (!hasNumber) error = "Phải có số";
        else if (!hasSpecial) error = "Phải có ký tự đặc biệt";
    }

    document.getElementById('passwordError').innerText = error;
});

// ===== CONFIRM =====
confirmInput.addEventListener('input', function () {
    let error = "";

    if (this.value !== passwordInput.value) {
        error = "Mật khẩu không khớp";
    }

    document.getElementById('confirmError').innerText = error;
});
</script>

@endsection
