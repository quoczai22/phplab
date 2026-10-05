@extends('admin.layouts.main')

@section('title', 'Danh mục')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tổng quan</a></li>
    <li class="breadcrumb-item active">Danh mục</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Quản lý Danh mục</h1>
    <a class="btn btn-primary" href="{{ route('admin.danhmuc.create') }}">
        <i class="bi bi-plus-lg me-1"></i> Thêm danh mục
    </a>
</div>

<form class="row row-cols-lg-auto g-2 align-items-center mb-3" method="get">
    <div class="col-12">
        <input name="kw" value="{{ $kw ?? '' }}" class="form-control" placeholder="Tìm theo tên/slug">
    </div>
    <div class="col-12">
        <button class="btn btn-outline-secondary">
            <i class="bi bi-search me-1"></i> Tìm
        </button>
    </div>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:80px">ID</th>
                    <th>Tên danh mục</th>
                    <th>Slug</th>
                    <th style="width:160px">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $r)
                    <tr>
                        <td>{{ $r->id }}</td>
                        <td class="fw-semibold">{{ $r->ten }}</td>
                        <td class="text-muted">{{ $r->slug }}</td>
                        <td>
                            <a class="btn btn-sm btn-outline-primary me-1" href="{{ route('admin.danhmuc.edit', $r) }}">
                                <i class="bi bi-pencil"></i> Sửa
                            </a>
                            <form class="d-inline" method="post" action="{{ route('admin.danhmuc.destroy', $r) }}" onsubmit="return confirm('Xóa danh mục này?')">
                                @csrf
                                @method('delete')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Không có dữ liệu danh mục</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($rows->hasPages())
        <div class="card-footer bg-white">
            {{ $rows->links() }}
        </div>
    @endif
</div>
@endsection
