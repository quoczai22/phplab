@extends('layouts.app')

@section('content')
<div class="text-center py-5">
    <h1 class="display-1 fw-bold text-danger">403</h1>
    <h3 class="mb-3">Truy cập bị từ chối</h3>
    <p class="text-muted mb-4">
        {{ $exception->getMessage() ?: 'Bạn không có quyền truy cập vào trang quản trị này.' }}
    </p>
    <a href="{{ url('/') }}" class="btn btn-dark px-4">Quay về Trang chủ</a>
</div>
@endsection

