@extends('layouts.app')

@section('content')
<div class="container mt-4" style="max-width: 400px;">
    <h3>Đăng nhập</h3>
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

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

        <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
    </form>
</div>
@endsection