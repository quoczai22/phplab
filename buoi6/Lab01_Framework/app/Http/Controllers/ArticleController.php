<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  Illuminate\Http\Response;
use  App\Models\Article;


class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles=[
        ['id' => 1, 'title' => 'Giới thiệu Laravel 12', 'body' => 'Nội dung A'],
        ['id' => 2, 'title' => 'Blade Components', 'body' => 'Nội dung B'],
    ];
       return view('articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'=> ['required','string','max:255'],
            'body' => ['required', 'string', 'min:10'],
        ]);
        return redirect()->route('articles.index')->with('success','Tao bai viet thanh cong');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return "Xem chi tiết bài viết ID: " . $article->id . " - Tiêu đề: " . $article->title;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = ['id' => $id, 'title' => 'Tiêu đề mẫu', 'body' => 'Nộidung mẫu'];
        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
    'title' => ['required', 'string', 'max:255'],
    'body' => ['required', 'string', 'min:10'],
    ]);
    return redirect()->route('articles.index')->with('success', "Cập nhật bài viết #$id thành công (demo).");
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
     
        // Vì là mockup tĩnh nên không thực sự lưu/xoá vào đâu cả, ta chỉ redirect kèm Flash Message để test giao diện
        return redirect()->route('articles.index')->with('success', "Đã giả lập xoá thành công bài viết ID: {$article->id}!");
    }
}