@extends('admin.layouts.main')

@section('title', 'Thêm danh mục')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tổng quan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.danhmuc.index') }}">Danh mục</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@endsection

@section('content')
<h1 class="h4 mb-3">Thêm danh mục</h1>

<form method="post" action="{{ route('admin.danhmuc.store') }}" class="card card-body shadow-sm" style="max-width: 600px;">
    @csrf
    <div class="mb-3">
        <label class="form-label fw-semibold">Tên danh mục <span class="text-danger">*</span></label>
        <input name="ten" value="{{ old('ten') }}" class="form-control @error('ten') is-invalid @enderror" placeholder="Nhập tên danh mục" required>
        @error('ten')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Slug</label>
        <input name="slug" value="{{ old('slug') }}" class="form-control @error('slug') is-invalid @enderror" placeholder="Để trống sẽ tự động tạo từ tên">
        @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> Lưu danh mục
        </button>
        <a class="btn btn-secondary" href="{{ route('admin.danhmuc.index') }}">Hủy</a>
    </div>
</form>
@endsection
