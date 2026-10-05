<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TinTuc;
use App\Models\DanhMuc;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPost = TinTuc::count();
        $trashPost = TinTuc::onlyTrashed()->count();
        $totalCat = DanhMuc::count();
        $publishedPost = TinTuc::where('trang_thai', 'published')->count();
        $draftPost = TinTuc::where('trang_thai', 'draft')->count();

        return view('admin.dashboard.index', compact('totalPost', 'trashPost', 'totalCat', 'publishedPost', 'draftPost'));
    }
}