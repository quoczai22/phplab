<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DanhMuc;
use Illuminate\Http\Request;
use App\Http\Requests\DanhMucRequest;
use Illuminate\Support\Str;

class DanhMucController extends Controller
{
    private function makeUniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = Str::slug($base) ?: Str::random(8);
        $original = $slug;
        $i = 2;
        while (
            DanhMuc::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '<>', $ignoreId))
                ->exists()
        ) {
            $slug = "{$original}-{$i}";
            $i++;
        }
        return $slug;
    }

    public function index(Request $request)
    {
        $q = DanhMuc::query();
        if ($kw = trim($request->get('kw', ''))) {
            $q->where(function($x) use ($kw) {
                $x->where('ten', 'like', "%{$kw}%")
                  ->orWhere('slug', 'like', "%{$kw}%");
            });
        }
        /** @var \Illuminate\Pagination\LengthAwarePaginator $rows */
        $rows = $q->latest('id')->paginate(10);
        $rows->withQueryString();
        return view('admin.danh_muc.index', compact('rows', 'kw'));
    }

    public function create()
    {
        return view('admin.danh_muc.create');
    }

    public function store(DanhMucRequest $request)
    {
        $data = $request->validated();
        if (blank($data['slug'] ?? null)) {
            $data['slug'] = $this->makeUniqueSlug($data['ten']);
        } else {
            $data['slug'] = $this->makeUniqueSlug($data['slug']);
        }
        DanhMuc::create($data);
        return redirect()->route('admin.danhmuc.index')->with('ok', 'Thêm danh mục thành công');
    }

    public function edit(DanhMuc $danhmuc)
    {
        return view('admin.danh_muc.edit', compact('danhmuc'));
    }

    public function update(DanhMucRequest $request, DanhMuc $danhmuc)
    {
        $data = $request->validated();
        $base = !empty($data['slug']) ? $data['slug'] : $data['ten'];
        $data['slug'] = $this->makeUniqueSlug($base, $danhmuc->id);
        $danhmuc->update($data);
        return redirect()->route('admin.danhmuc.index')->with('ok', 'Cập nhật danh mục thành công');
    }

    public function destroy(DanhMuc $danhmuc)
    {
        if ($danhmuc->tins()->exists()) {
            return back()->withErrors(['error' => 'Không thể xóa danh mục này vì đang có bài viết thuộc danh mục!']);
        }
        $danhmuc->delete();
        return back()->with('ok', 'Đã xóa danh mục');
    }
}