<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyPageController extends Controller
{
  /**
     * ルーティングで指定されたURLへのリクエストがあったときに実行されるメソッド
     */
    public function index()
    {
        // ここでデータ取得などの処理を行うことが多いです
        // $data = ...;

        // 'my-page' という名前のViewファイル（画面ファイル）を読み込んで表示せよ、という指示
        return view('my-page');
        
        // データをViewに渡す場合は
        // return view('my-page', ['data' => $data, 'id' => $id]);や
        // return view('my-page', compact('data', 'id'));
        // のようにします
        
        // ビューファイルがサブディレクトリにある場合、ドット(.)で区切って指定します。
        // 例: resources/views/admin/users/index.blade.php を表示する場合
        // return view('admin.users.index'); // <-- サブディレクトリの例
    }
}
