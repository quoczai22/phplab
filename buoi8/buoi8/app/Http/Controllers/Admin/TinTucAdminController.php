<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TinTucRequest;
use App\Models\TinTuc;
use App\Models\DanhMuc;
use App\Models\HinhAnhTinTuc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TinTucAdminController extends Controller
{
    /**
     * Tự phát sinh slug duy nhất cho tin_tucs
     */
    private function makeUniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = Str::slug($base) ?: Str::random(8);
        $original = $slug;
        $i = 2;
        while (
            TinTuc::withTrashed()
                ->where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '<>', $ignoreId))
                ->exists()
        ) {
            $slug = "{$original}-{$i}";
            $i++;
        }
        return $slug;
    }

    /**
     * Danh sách bài viết & Tìm kiếm nâng cao (Bài tập 07)
     */
    public function index(Request $request)
    {
        $q = TinTuc::query()->with('danhMuc');

        // Tìm kiếm đa tiêu chí
        $q->when($request->filled('kw'), fn($x) => $x->where(function($sub) use ($request) {
            $sub->where('tieude', 'like', "%{$request->kw}%")
                ->orWhere('slug', 'like', "%{$request->kw}%");
        }))
        ->when($request->filled('danhmuc_id'), fn($x) => $x->where('danhmuc_id', $request->danhmuc_id))
        ->when($request->filled('trang_thai'), fn($x) => $x->where('trang_thai', $request->trang_thai))
        ->when($request->filled('from'), fn($x) => $x->whereDate('ngaydang', '>=', $request->from))
        ->when($request->filled('to'), fn($x) => $x->whereDate('ngaydang', '<=', $request->to));

        // Lọc xem thùng rác
        if ($request->boolean('trash')) {
            $q->onlyTrashed();
        }

        /** @var \Illuminate\Pagination\LengthAwarePaginator $rows */
        $rows = $q->orderByDesc('id')->paginate(10);
        $rows->withQueryString();
        $danhMucs = DanhMuc::orderBy('ten')->get();

        return view('admin.tin.index', compact('rows', 'danhMucs'));
    }

    public function create()
    {
        $dm = DanhMuc::orderBy('ten')->get();
        return view('admin.tin.create', compact('dm'));
    }

    public function store(TinTucRequest $request)
    {
        $data = $request->validated();

        // Tự sinh slug duy nhất (Bài tập 06)
        if (blank($data['slug'] ?? null)) {
            $data['slug'] = $this->makeUniqueSlug($data['tieude']);
        } else {
            $data['slug'] = $this->makeUniqueSlug($data['slug']);
        }

        // Xử lý trạng thái và ngày đăng (Bài tập 05)
        $data['trang_thai'] = $data['trang_thai'] ?? 'draft';
        if ($data['trang_thai'] === 'published' && empty($data['ngaydang'])) {
            $data['ngaydang'] = now()->toDateString();
        }

        // Upload ảnh đại diện chính
        if ($request->hasFile('hinhanh_up')) {
            $data['hinhanh_path'] = $request->file('hinhanh_up')->store('news', 'public');
        }

        $tin = TinTuc::create($data);

        // Upload nhiều ảnh phụ cho gallery (Bài tập nâng cao)
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $path = $file->store('news/gallery', 'public');
                $tin->hinhAnhs()->create([
                    'duongdan' => $path,
                ]);
            }
        }

        return redirect()->route('admin.tin.index')->with('ok', 'Đã thêm bài viết mới thành công');
    }

    public function show(TinTuc $tin)
    {
        return redirect()->route('admin.tin.edit', $tin);
    }

    public function edit(TinTuc $tin)
    {
        $dm = DanhMuc::orderBy('ten')->get();
        $tin->load('hinhAnhs');
        return view('admin.tin.edit', compact('tin', 'dm'));
    }

    public function update(TinTucRequest $request, TinTuc $tin)
    {
        $data = $request->validated();

        // Tự sinh slug duy nhất khi sửa nếu để trống
        $base = !empty($data['slug']) ? $data['slug'] : $data['tieude'];
        $data['slug'] = $this->makeUniqueSlug($base, $tin->id);

        // Xử lý chuyển draft -> published thì cập nhật ngaydang nếu chưa có
        $data['trang_thai'] = $data['trang_thai'] ?? 'draft';
        if ($data['trang_thai'] === 'published' && empty($tin->ngaydang) && empty($data['ngaydang'])) {
            $data['ngaydang'] = now()->toDateString();
        }

        // Upload ảnh đại diện mới
        if ($request->hasFile('hinhanh_up')) {
            if ($tin->hinhanh_path && Storage::disk('public')->exists($tin->hinhanh_path)) {
                Storage::disk('public')->delete($tin->hinhanh_path);
            }
            $data['hinhanh_path'] = $request->file('hinhanh_up')->store('news', 'public');
        }

        $tin->update($data);

        // Upload thêm ảnh gallery phụ nếu có
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $path = $file->store('news/gallery', 'public');
                $tin->hinhAnhs()->create([
                    'duongdan' => $path,
                ]);
            }
        }

        return redirect()->route('admin.tin.index')->with('ok', 'Đã cập nhật bài viết thành công');
    }

    public function destroy(TinTuc $tin)
    {
        $tin->delete(); // Soft delete
        return back()->with('ok', 'Đã đưa bài viết vào thùng rác');
    }

    public function restore($id)
    {
        $tin = TinTuc::withTrashed()->findOrFail($id);
        $tin->restore();
        return back()->with('ok', 'Đã khôi phục bài viết');
    }

    public function forceDelete($id)
    {
        $tin = TinTuc::withTrashed()->with('hinhAnhs')->findOrFail($id);

        // Xóa file ảnh đại diện chính
        if ($tin->hinhanh_path && Storage::disk('public')->exists($tin->hinhanh_path)) {
            Storage::disk('public')->delete($tin->hinhanh_path);
        }

        // Xóa tất cả các file ảnh phụ trong gallery
        foreach ($tin->hinhAnhs as $img) {
            if ($img->duongdan && Storage::disk('public')->exists($img->duongdan)) {
                Storage::disk('public')->delete($img->duongdan);
            }
            $img->delete();
        }

        $tin->forceDelete();
        return back()->with('ok', 'Đã xóa vĩnh viễn bài viết và toàn bộ hình ảnh');
    }

    // Xóa từng ảnh phụ trong gallery
    public function deleteGalleryImage($id)
    {
        $img = HinhAnhTinTuc::findOrFail($id);
        if ($img->duongdan && Storage::disk('public')->exists($img->duongdan)) {
            Storage::disk('public')->delete($img->duongdan);
        }
        $img->delete();
        return back()->with('ok', 'Đã xóa ảnh phụ trong gallery');
    }
}
