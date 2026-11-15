@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-3">
        <label>Email hoặc SĐT</label>
        <input type="text" name="login" class="form-control" value="{{ old('login') }}">
        @error('login')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3">
        <label>Mật khẩu</label>
        <input type="password" name="password" class="form-control">
        @error('password')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <button class="btn btn-primary">Đăng nhập</button>
</form>

@endsection
