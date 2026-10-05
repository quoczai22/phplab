@extends('admin.layouts.main')

@section('title', 'Sửa bài viết')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tổng quan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.tin.index') }}">Tin tức</a></li>
    <li class="breadcrumb-item active">Sửa bài viết #{{ $tin->id }}</li>
@endsection

@section('content')
<h1 class="h4 mb-3">Sửa bài viết #{{ $tin->id }}</h1>

<form class="card card-body shadow-sm" method="post" action="{{ route('admin.tin.update', $tin) }}" enctype="multipart/form-data">
    @csrf
    @method('put')

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="mb-3">
                <label class="form-label fw-semibold">Tiêu đề <span class="text-danger">*</span></label>
                <input name="tieude" value="{{ old('tieude', $tin->tieude) }}" class="form-control @error('tieude') is-invalid @enderror" required>
                @error('tieude')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Slug (đường dẫn thân thiện)</label>
                <input name="slug" value="{{ old('slug', $tin->slug) }}" class="form-control @error('slug') is-invalid @enderror" placeholder="Để trống hệ thống sẽ tự động cập nhật từ tiêu đề">
                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Tóm tắt</label>
                <textarea name="tomtat" rows="3" class="form-control @error('tomtat') is-invalid @enderror">{{ old('tomtat', $tin->tomtat) }}</textarea>
                @error('tomtat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Nội dung <span class="text-danger">*</span></label>
                <textarea name="noidung" rows="8" class="form-control @error('noidung') is-invalid @enderror" required>{{ old('noidung', $tin->noidung) }}</textarea>
                @error('noidung')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Gallery ảnh phụ (Bài tập nâng cao) --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Bộ sưu tập ảnh phụ (Gallery)</label>
                @if($tin->hinhAnhs && $tin->hinhAnhs->count() > 0)
                    <div class="row g-2 mb-3">
                        @foreach($tin->hinhAnhs as $img)
                            <div class="col-6 col-md-4 col-lg-3 text-center">
                                <div class="position-relative border rounded p-1 bg-white">
                                    <img src="{{ $img->url }}" class="img-fluid rounded" style="height: 100px; object-fit: cover; width: 100%;" alt="Gallery">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 p-0 px-1" onclick="deleteGallery('{{ route('admin.tin.delete-gallery', $img->id) }}')">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted small mb-2">Chưa có ảnh phụ nào trong gallery.</div>
                @endif
                <label class="form-label small text-muted">Chọn thêm ảnh phụ tải lên:</label>
                <input type="file" name="gallery_images[]" multiple class="form-control @error('gallery_images.*') is-invalid @enderror" accept="image/*">
                @error('gallery_images.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card bg-light p-3 border-0 mb-3">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Trạng thái xuất bản</label>
                    <select name="trang_thai" class="form-select @error('trang_thai') is-invalid @enderror">
                        <option value="draft" @selected(old('trang_thai', $tin->trang_thai) == 'draft')>Nháp (Draft)</option>
                        <option value="published" @selected(old('trang_thai', $tin->trang_thai) == 'published')>Đã đăng (Published)</option>
                    </select>
                    @error('trang_thai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Danh mục</label>
                    <select name="danhmuc_id" class="form-select @error('danhmuc_id') is-invalid @enderror">
                        <option value="">-- Không chọn danh mục --</option>
                        @foreach($dm as $c)
                            <option value="{{ $c->id }}" @selected(old('danhmuc_id', $tin->danhmuc_id) == $c->id)>{{ $c->ten }}</option>
                        @endforeach
                    </select>
                    @error('danhmuc_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Ngày đăng</label>
                    <input type="date" name="ngaydang" value="{{ old('ngaydang', optional($tin->ngaydang)->toDateString()) }}" class="form-control @error('ngaydang') is-invalid @enderror">
                    @error('ngaydang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Ảnh đại diện chính hiện tại</label>
                    <div class="mb-2">
                        <img id="current-image" src="{{ $tin->thumb_url }}" class="rounded border" style="width:100%;max-height:180px;object-fit:cover" alt="Ảnh hiện tại">
                    </div>
                    <label class="form-label small text-muted">Chọn ảnh mới để thay thế:</label>
                    <input type="file" name="hinhanh_up" class="form-control @error('hinhanh_up') is-invalid @enderror" accept="image/*" onchange="previewImage(this)">
                    <div class="form-text">jpg, jpeg, png, webp ≤ 2MB</div>
                    @error('hinhanh_up')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3 pt-3 border-top d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-arrow-repeat me-1"></i> Cập nhật bài viết
        </button>
        <a class="btn btn-secondary" href="{{ route('admin.tin.index') }}">Quay lại</a>
    </div>
</form>

{{-- Form ẩn để xóa ảnh gallery --}}
<form id="form-delete-gallery" method="post" style="display: none;">
    @csrf
    @method('delete')
</form>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('current-image');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function deleteGallery(url) {
        if (confirm('Bạn có chắc muốn xóa ảnh phụ này khỏi bài viết?')) {
            const form = document.getElementById('form-delete-gallery');
            form.action = url;
            form.submit();
        }
    }
</script>
@endpush
