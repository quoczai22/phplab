@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white text-center">
                <h4 class="mb-0">Đăng nhập Admin</h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.login.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email hoặc Số điện thoại</label>
                        <input type="text" name="username" class="form-control" placeholder="Nhập email hoặc SĐT admin" value="{{ old('username') }}">
                        @error('username') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu">
                        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Đăng nhập Quản trị</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection