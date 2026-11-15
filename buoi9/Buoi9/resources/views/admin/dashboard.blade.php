@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">&#9881; Trang quản trị (Admin Dashboard)</h3>
        <p class="text-muted mb-0">
            Xin chào, <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->email }})
        </p>
    </div>
</div>

{{-- Thống kê tổng quan --}}
<div class="row g-4 mb-4 text-center">
    <div class="col-md-4">
        <div class="card border-dark shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title text-muted">Tổng số trạm xe</h5>
                <h2 class="display-6 fw-bold text-primary my-2">{{ $totalStations ?? 0 }}</h2>
                <a href="{{ route('admin.stations.index') }}" class="btn btn-outline-dark btn-sm mt-2">
                    Xem danh sách trạm &rarr;
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-dark shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title text-muted">Tổng số xe đạp</h5>
                <h2 class="display-6 fw-bold text-success my-2">{{ $totalBicycles ?? 0 }}</h2>
                <a href="{{ route('admin.bicycles.index') }}" class="btn btn-outline-dark btn-sm mt-2">
                    Xem danh sách xe &rarr;
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-dark shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title text-muted">Người dùng (User)</h5>
                <h2 class="display-6 fw-bold text-secondary my-2">{{ $totalUsers ?? 0 }}</h2>
                <span class="badge bg-secondary mt-2">Tài khoản thành viên</span>
            </div>
        </div>
    </div>
</div>

{{-- Chức năng quản lý --}}
<div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
        <strong>Chức năng quản lý hệ thống</strong>
    </div>
    <div class="card-body d-flex justify-content-center flex-wrap gap-3 py-4">
        <a href="{{ route('admin.stations.index') }}" class="btn btn-dark px-4 py-3">
            Quản lý trạm xe
        </a>

        <a href="{{ route('admin.stations.create') }}" class="btn btn-outline-primary px-4 py-3">
            + Thêm trạm xe mới
        </a>

        <a href="{{ route('admin.bicycles.index') }}" class="btn btn-dark px-4 py-3">
            Quản lý xe đạp
        </a>

        <a href="{{ route('admin.bicycles.create') }}" class="btn btn-outline-success px-4 py-3">
            + Thêm xe đạp mới
        </a>

        <a href="{{ route('seed.faker') }}" class="btn btn-warning px-4 py-3" onclick="return confirm('Tạo thêm dữ liệu mẫu bằng Faker?')">
            &#9889; Tạo dữ liệu mẫu (Faker)
        </a>
    </div>
</div>

@endsection