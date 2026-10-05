@extends('admin.layouts.main')

@section('title', 'Sửa danh mục')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tổng quan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.danhmuc.index') }}">Danh mục</a></li>
    <li class="breadcrumb-item active">Sửa danh mục #{{ $danhmuc->id }}</li>
@endsection

@section('content')
<h1 class="h4 mb-3">Sửa danh mục #{{ $danhmuc->id }}</h1>

<form method="post" action="{{ route('admin.danhmuc.update', $danhmuc) }}" class="card card-body shadow-sm" style="max-width: 600px;">
    @csrf
    @method('put')

    <div class="mb-3">
        <label class="form-label fw-semibold">Tên danh mục <span class="text-danger">*</span></label>
        <input name="ten" value="{{ old('ten', $danhmuc->ten) }}" class="form-control @error('ten') is-invalid @enderror" required>
        @error('ten')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Slug</label>
        <input name="slug" value="{{ old('slug', $danhmuc->slug) }}" class="form-control @error('slug') is-invalid @enderror" placeholder="Để trống sẽ tự động tạo từ tên">
        @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-arrow-repeat me-1"></i> Cập nhật
        </button>
        <a class="btn btn-secondary" href="{{ route('admin.danhmuc.index') }}">Quay lại</a>
    </div>
</form>
@endsection
