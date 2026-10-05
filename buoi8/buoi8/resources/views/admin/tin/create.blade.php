@extends('admin.layouts.main')

@section('title', 'Thêm bài viết')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tổng quan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.tin.index') }}">Tin tức</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@endsection

@section('content')
<h1 class="h4 mb-3">Thêm bài viết mới</h1>

<form class="card card-body shadow-sm" method="post" action="{{ route('admin.tin.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="mb-3">
                <label class="form-label fw-semibold">Tiêu đề <span class="text-danger">*</span></label>
                <input name="tieude" value="{{ old('tieude') }}" class="form-control @error('tieude') is-invalid @enderror" placeholder="Nhập tiêu đề bài viết" required>
                @error('tieude')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Slug (đường dẫn thân thiện)</label>
                <input name="slug" value="{{ old('slug') }}" class="form-control @error('slug') is-invalid @enderror" placeholder="Để trống hệ thống sẽ tự động tạo từ tiêu đề">
                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Tóm tắt</label>
                <textarea name="tomtat" rows="3" class="form-control @error('tomtat') is-invalid @enderror" placeholder="Nhập tóm tắt ngắn">{{ old('tomtat') }}</textarea>
                @error('tomtat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Nội dung <span class="text-danger">*</span></label>
                <textarea name="noidung" rows="8" class="form-control @error('noidung') is-invalid @enderror" placeholder="Nhập nội dung bài viết..." required>{{ old('noidung') }}</textarea>
                @error('noidung')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Gallery ảnh phụ (Bài tập nâng cao) --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Bộ sưu tập ảnh phụ (Gallery)</label>
                <input type="file" name="gallery_images[]" multiple class="form-control @error('gallery_images.*') is-invalid @enderror" accept="image/*">
                <div class="form-text">Bạn có thể chọn cùng lúc nhiều ảnh phụ (jpg, png, webp ≤ 2MB).</div>
                @error('gallery_images.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card bg-light p-3 border-0 mb-3">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Trạng thái xuất bản</label>
                    <select name="trang_thai" class="form-select @error('trang_thai') is-invalid @enderror">
                        <option value="draft" @selected(old('trang_thai') == 'draft')>Nháp (Draft)</option>
                        <option value="published" @selected(old('trang_thai') == 'published')>Đã đăng (Published)</option>
                    </select>
                    @error('trang_thai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Danh mục</label>
                    <select name="danhmuc_id" class="form-select @error('danhmuc_id') is-invalid @enderror">
                        <option value="">-- Không chọn danh mục --</option>
                        @foreach($dm as $c)
                            <option value="{{ $c->id }}" @selected(old('danhmuc_id') == $c->id)>{{ $c->ten }}</option>
                        @endforeach
                    </select>
                    @error('danhmuc_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Ngày đăng</label>
                    <input type="date" name="ngaydang" value="{{ old('ngaydang', now()->toDateString()) }}" class="form-control @error('ngaydang') is-invalid @enderror">
                    @error('ngaydang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Ảnh đại diện chính</label>
                    <input type="file" name="hinhanh_up" class="form-control @error('hinhanh_up') is-invalid @enderror" accept="image/*" onchange="previewImage(this)">
                    <div class="form-text">jpg, jpeg, png, webp ≤ 2MB</div>
                    @error('hinhanh_up')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    
                    <div class="mt-3 text-center" id="preview-container" style="display: none;">
                        <img id="image-preview" src="#" alt="Xem trước ảnh" class="rounded border img-fluid" style="max-height: 160px; object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3 pt-3 border-top d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> Lưu bài viết
        </button>
        <a class="btn btn-secondary" href="{{ route('admin.tin.index') }}">Hủy</a>
    </div>
</form>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const container = document.getElementById('preview-container');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
