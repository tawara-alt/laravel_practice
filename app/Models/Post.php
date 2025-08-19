<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request; // Requestクラスをインポート
use Illuminate\Database\Eloquent\SoftDeletes; // SoftDeletesトレイトをインポート

class Post extends Model
{
     use HasFactory, SoftDeletes; // SoftDeletesトレイトを使用するよう追加

    // savePostメソッドで個別にプロパティを設定するため、$fillableは必須ではありませんが、
    // create()など他のLaravelの機能を使う場合に備えて残しておくと良いでしょう。
    protected $fillable = ['title', 'body', 'published_at'];

    /**
     * 投稿データをモデルに設定し、保存するカスタムメソッド
     *
     * @param \Illuminate\Http\Request $request 送信されたリクエストオブジェクト
     * @return void
     */
    public function savePost(Request $request)
    {
        // $request オブジェクトから直接データを取得し、モデルのプロパティに割り当てる
        $this->title = $request->input('title');
        $this->body = $request->input('body');
        
        // published_at は nullable なので、空文字列の場合には null を設定
        $this->published_at = !empty($request->input('published_at')) ? $request->input('published_at') : null;

        // 登録処理
        $this->save();
    }
}
