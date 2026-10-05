@extends('admin.layouts.main')

@section('title', 'Tin tức')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tổng quan</a></li>
    <li class="breadcrumb-item active">Tin tức</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Danh sách bài viết</h1>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-secondary" href="{{ route('admin.tin.index', array_merge(request()->query(), ['trash' => request('trash') ? 0 : 1])) }}">
            <i class="bi bi-trash3 me-1"></i> {{ request('trash') ? 'Xem tất cả' : 'Xem thùng rác' }}
        </a>
        <a class="btn btn-primary" href="{{ route('admin.tin.create') }}">
            <i class="bi bi-plus-lg me-1"></i> Thêm bài viết
        </a>
    </div>
</div>

{{-- Bộ lọc tìm kiếm nâng cao (Bài tập 07) --}}
<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="get" action="{{ route('admin.tin.index') }}" class="row g-2 align-items-end">
            @if(request('trash'))
                <input type="hidden" name="trash" value="1">
            @endif

            <div class="col-md-3">
                <label class="form-label small fw-semibold">Từ khóa:</label>
                <input name="kw" value="{{ request('kw') }}" class="form-control form-control-sm" placeholder="Tìm theo tiêu đề, slug...">
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-semibold">Danh mục:</label>
                <select name="danhmuc_id" class="form-select form-control-sm">
                    <option value="">-- Tất cả danh mục --</option>
                    @foreach($danhMucs as $cat)
                        <option value="{{ $cat->id }}" @selected(request('danhmuc_id') == $cat->id)>{{ $cat->ten }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-semibold">Trạng thái:</label>
                <select name="trang_thai" class="form-select form-control-sm">
                    <option value="">-- Tất cả --</option>
                    <option value="published" @selected(request('trang_thai') == 'published')>Đã đăng</option>
                    <option value="draft" @selected(request('trang_thai') == 'draft')>Nháp</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-semibold">Từ ngày:</label>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control form-control-sm">
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-semibold">Đến ngày:</label>
                <input type="date" name="to" value="{{ request('to') }}" class="form-control form-control-sm">
            </div>

            <div class="col-md-1">
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    <i class="bi bi-filter"></i> Lọc
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:60px">ID</th>
                    <th style="width:100px">Ảnh</th>
                    <th>Tiêu đề</th>
                    <th>Slug</th>
                    <th>Danh mục</th>
                    <th style="width:110px">Trạng thái</th>
                    <th style="width:110px">Ngày đăng</th>
                    <th style="width:190px">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $r)
                    <tr class="{{ $r->deleted_at ? 'opacity-50 table-secondary' : '' }}">
                        <td>{{ $r->id }}</td>
                        <td>
                            <img src="{{ $r->thumb_url }}" class="rounded border" style="width:85px;height:55px;object-fit:cover" alt="Ảnh">
                        </td>
                        <td>
                            <div class="fw-semibold text-truncate" style="max-width: 250px;">{{ $r->tieude }}</div>
                        </td>
                        <td class="text-muted small text-truncate" style="max-width: 150px;">
                            {{ $r->slug ?? '-' }}
                        </td>
                        <td class="text-muted">{{ $r->danhMuc->ten ?? '-' }}</td>
                        <td>
                            @if($r->trang_thai === 'published')
                                <span class="badge bg-success">Đã đăng</span>
                            @else
                                <span class="badge bg-secondary">Nháp</span>
                            @endif
                        </td>
                        <td>{{ optional($r->ngaydang)->format('d/m/Y') }}</td>
                        <td>
                            @if(!$r->deleted_at)
                                <a class="btn btn-sm btn-outline-primary me-1" href="{{ route('admin.tin.edit', $r) }}">
                                    <i class="bi bi-pencil"></i> Sửa
                                </a>
                                <form class="d-inline" method="post" action="{{ route('admin.tin.destroy', $r) }}" onsubmit="return confirm('Xóa (đưa vào thùng rác)?')">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </form>
                            @else
                                <form class="d-inline" method="post" action="{{ route('admin.tin.restore', $r->id) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-success me-1">
                                        <i class="bi bi-arrow-counterclockwise"></i> Khôi phục
                                    </button>
                                </form>
                                <form class="d-inline" method="post" action="{{ route('admin.tin.force-delete', $r->id) }}" onsubmit="return confirm('Xóa vĩnh viễn bài viết này?')">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-x-circle"></i> Xóa vĩnh viễn
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Không tìm thấy bài viết nào phù hợp</td>
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
