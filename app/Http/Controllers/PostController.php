<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return view('admin.posts.index', compact('posts'));
    }

      public function show($id)
    {
        // 指定IDの記事を取得。見つからなければ404エラー
        $post = Post::findOrFail($id);

        // ビューに記事データを渡して表示
        return view('admin.posts.show', compact('post'));
    }

     public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        // バリデーション
        $validator = $this->validatePost($request);

        // バリデーションに失敗した場合
        if ($validator->fails()) {
            // リダイレクト先を admin.posts.create ルートに変更
            return redirect(route('admin.posts.create')) 
                ->withErrors($validator) // エラーメッセージをセッションに保存
                ->withInput(); // 直前に入力されたデータをセッションに保存
        }

        // Postモデルのカスタムメソッドを使ってデータを保存
        $post = new Post();
        // $request オブジェクトを直接 savePost メソッドに渡す
        $post->savePost($request); 

        // /admin/posts にリダイレクトする（既に定義済みの記事一覧ページなどへ）
        // 成功メッセージをセッションにフラッシュデータとして保存
        return redirect(route('admin.posts.index'))->with('success', '記事が正常に投稿されました。');
    }

      public function edit($id)
    {
        // 指定IDの記事を取得。見つからなければ404エラー
        $post = Post::findOrFail($id);
        
        // 補足: $post という変数名は $_POST と似ているため混同する可能性があります。
        // これを避けるには、例えば全件なら $postList、1件なら $postData のように、より具体的な変数名を使用すると良いでしょう。

        // 新規作成時と同じビュー ('posts.create') を再利用し、記事データを渡す
        return view('admin.posts.create', compact('post'));
    }

    public function update(Request $request, $id)
    {
        // バリデーション (新規作成時と同じ validatePost メソッドを再利用)
        $validator = $this->validatePost($request);

        // バリデーションに失敗した場合
        if ($validator->fails()) {
            // 編集フォームのルートにリダイレクト
            return redirect(route('admin.posts.edit', $id))
                ->withErrors($validator) // エラーメッセージをセッションに保存
                ->withInput(); // 直前に入力されたデータをセッションに保存
        }

        // 更新対象の記事を取得
        $post = Post::findOrFail($id);

        // Postモデルのカスタムメソッドを使ってデータを更新
        // savePost メソッドは、既存のインスタンスに対して呼び出すことで更新処理を行う
        $post->savePost($request);

        // 記事一覧ページへリダイレクトし、成功メッセージを表示
        return redirect(route('admin.posts.index'))->with('success', '記事が正常に更新されました。');
    }

      public function destroy($id)
    {
        // 削除対象の記事を取得。見つからなければ404エラー
        $post = Post::findOrFail($id);

        // 論理削除を実行
        $post->delete(); // SoftDeletesトレイトを使用していれば、deleted_atカラムが更新される

        // 記事一覧ページへリダイレクトし、成功メッセージを表示
        return redirect(route('admin.posts.index'))->with('success', '記事が正常に削除されました。');
    }

    // validatePost メソッドは変更がないためコードは省略
    // protected function validatePost(Request $request) { ... }

    /**
     * 投稿データに対するバリデーションルールを定義し、適用する
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validatePost(Request $request)
    {
        $rules = [
            'title' => 'required|max:255',
            'body' => 'required',
            'published_at' => 'nullable|date_format:Y-m-d\TH:i', // HTMLのdatetime-local形式に対応
        ];

        $messages = [
            'title.required' => ':attributeは必須項目です。',
            'title.max' => ':attributeは:max文字以内で入力してください。',
            'body.required' => ':attributeは必須項目です。',
            'published_at.date_format' => ':attributeは正しい日時形式で入力してください。',
        ];
        
        $attributes = [
            'title' => 'タイトル',
            'body' => '本文',
            'published_at' => '公開日時',
        ];

        return Validator::make($request->all(), $rules, $messages, $attributes);
    }
}
