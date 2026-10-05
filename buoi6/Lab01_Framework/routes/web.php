<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

route::get('/articles/page/{page}', function ($page) {
    return "Trang bai viet tham so: ".(int) $page;
})->whereNumber("page")->name("articles.page");

Route::get("/articles/slug/{slug?}", function ($slug="Khong co slug"){
    return view("Slug: ".$slug);
} )->where("slug","[a-z0-9]+");

Route:: prefix('admin')->group( function (){
Route::get('/articles', fn() => 'Quản trị bài viết')
->name('admin.articles.index');
});

Route::resource('articles', ArticleController::class);

Route::get('/articles/show/{article}', [ArticleController::class, 'show'])->name('articles.show');

Route::delete('/articles/{id}', [ArticleController::class, 'destroy'])->name('articles.destroy');


