<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyPageController;
//use App\Http\Controllers\UserController; // UserControllerを使うために追記
use App\Http\Controllers\PostController;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin/posts', [PostController::class, 'index'])->name('admin.posts.index');
    Route::get('/admin/posts/detail/{id}', [PostController::class, 'show'])->name('admin.posts.show');
    Route::get('/admin/posts/create', [PostController::class, 'create'])->name('admin.posts.create');
    Route::post('/admin/posts/store', [PostController::class, 'store'])->name('admin.posts.store');
    // 編集フォームの表示
    Route::get('/admin/posts/{id}/edit', [PostController::class, 'edit'])->name('admin.posts.edit');
    // 更新処理の実行
    Route::put('/admin/posts/{id}/update', [PostController::class, 'update'])->name('admin.posts.update');
    // 削除処理の実行 (DELETEリクエスト)
    Route::delete('/admin/posts/{id}/delete', [PostController::class, 'destroy'])->name('admin.posts.delete');
});
    //ログインユーザのみアクセス可能
Route::middleware('auth')->group(function () {

    Route::get('/tasks/download', [TaskController::class, 'downloadCsv'])->name('tasks.downloadCsv');

    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    //　/tasks/create というURLにアクセスすると,TaskController の create() メソッドが呼ばれ,ルート名は 'tasks.create' になるよ
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
     //　/tasks/1とか2とか というURLにアクセスすると,TaskController の show() メソッドが呼ばれ,ルート名は 'tasks.show' になるよ
    Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('tasks.show');

    Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    

});

require __DIR__.'/auth.php';

// --- Routeの定義例 ---

// MyPageControllerクラスのindexメソッドを実行するルート。GETメソッドのみアクセスできる
// 名前を付けることが出来る（ドット区切りが推奨）
Route::get('/my-page', [MyPageController::class, 'index'])->name('my.page');

// URLを変えたとしてもnameを変えてなければ、nameからurlを取得できるので、影響範囲が少なくなる
// nameはエイリアスに近い認識
// Route::get('/my-page-2', [MyPageController::class, 'index'])->name('my.page');

// use を使わない場合（クラス名を直接指定）
// Route::get('/fqcn-example', [\App\Http\Controllers\MyPageController::class, 'fqcnMethod']);


// パラメータを含むルート定義 {id} の部分がパラメータ
// /user/5 のようにアクセスすると、コントローラーの show メソッドに 5 が渡されます。
//Route::get('/user/{id}/{mode}', [UserController::class, 'show']);
// コントローラーのメソッドは public function show($id, $mode) のようになります。


// パラメータを任意（省略可能）にする {id?} のように ? を付ける
// /user/5 や /user のどちらでもアクセスできます。
//Route::get('/optional-user/{id?}', [UserController::class, 'show']); // ルートパスを optional-user に変更して区別
// コントローラーのメソッドは public function show($id = null) のようになります。


// コントローラーを使わず、直接処理を書くルート
// 簡単な処理やページ表示などに使えます
Route::get('/about', function () {
    // 直接ビューを返す例
    return view('about');
});