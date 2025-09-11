<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
//use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function index(Request $request) //$requestは、ユーザーが検索フォームに入力した値を保持するオブジェクト。
    {
        $users = User::all();
        $query = Task::query(); //Taskモデルに対して、query()メソッドを使って、クエリビルダーを開始すると宣言。つまりTaskモデルに対して、検索条件を組み立てる準備をしますよという宣言。
                                //$queryは、どう検索するか？の条件を持っている。

        //条件をつなげていくことで、SQLを構築。
        if ($request->filled('title')){
            $query->where('title', 'like' , '%'. $request->title .'%'); //テストと入力した場合、$requestはテストという値を含んでいる。$queryはテストという条件を持っているというイメージ。
        }

        if ($request->filled('user_id') && $request->user_id !== 'all'){
            $query->where('user_id',$request->user_id);
        }

        if ($request->filled('status')){
            $query->whereIn('status',$request->input('status'));
        }

        if ($request->filled('priority')){
            $query->whereIn('priority',$request->input('priority'));
        }

        if ($request->filled('deadline_from')){
            $query->where('deadline_at','>=',$request->input('deadline_from'));
        }

        if ($request->filled('deadline_to')){
            $query->where('deadline_at','<=',$request->input('deadline_to'));
        }
        
        //ここまで$queryに積み重ねてきた検索条件を元に、最終的な結果を取得する処理。
        $tasks = $query->orderBy('updated_at','desc')->paginate(10);
        $users = \App\Models\User::all();
        return view('tasks.index', compact('tasks','users')); //一覧ページに表示
    }

    public function downloadCsv(Request $request)
    {
        Log::info('downloadCsv method reached');
               
        $query = Task::query();

        if ($request->filled('title')){
            $query->where('title', 'like' , '%'. $request->title . '%');
        }

        if ($request->filled('user_id') && $request->user_id !== 'all') {
            $query->where('user_id',$request->user_id);
        }

        if ($request->filled('status')){
            $query->whereIn('status',$request->input('status'));
        }
        
        if ($request->filled('priority')){
            $query->whereIn('priority',$request->input('priority'));
        }

        if ($request->filled('deadline_from')){
            $query->where('deadline_at','>=',$request->input('deadline_from'));
        }

        if ($request->filled('deadline_to')){
            $query->where('deadline_at','<=',$request->input('deadline_to'));
        }
        //　↑ ここまではindex()メソッドと同じようにユーザーが入力した検索条件に応じて、Taskモデルを絞り込む処理。①

        $tasks = $query->with('user')->orderBy('updated_at','desc')->get(); //Csv用に全件データ取得。②
        
        $csvHeader = ['ID','タイトル','担当者','対応期限','優先度','ステータス','最終更新日時'];
        $csvData = [];

        foreach($tasks as $task) {
            $csvData[] = [
                $task->id,
                $task->title,
                $task->user->name ?? '未設定',
                optional($task->deadline_at)->format('Y-m-d H:i:s') ?? '未定',
                config('const.task.priority')[$task->priority] ?? '未定義',
                config('const.task.status')[$task->status] ?? '未定義',
                $task->updated_at->format('Y-m-d H:i:s'),
            ];
        }        
        //ここまでが画面で表示される内容と同じ情報を整形している処理。③

        $stream = fopen('php://temp','r+b'); //仮想ファイル（メモリ上）を開く。　→　書き込み先を用意
        fputcsv($stream,$csvHeader); //ヘッダー行を書き込む。　→　列名を記録

        foreach ($csvData as $row) {
            fputcsv($stream,$row); //データ行を1行ずつ書き込む　→　実データを記録
        }

        rewind($stream);
        $csvOutput = stream_get_contents($stream);
        fclose($stream);

        return response($csvOutput)
        ->header('Content-Type','text/csv')
        ->header('Content-Disposition', 'attachment; filename= "tasks.csv"');
            
    }

    public function create() //新規作成フォームを表示。
    {
        $users = User::all(); // ユーザー一覧を取得
        return view('tasks.create', compact('users'));
    }

    public function store(Request $request) //新しいタスクを保存。
    {
        $validator = $this->validateTask($request); //ユーザーが送信したデータ（$request）に対して、validateTask というメソッドでバリデーション（入力チェック）を行う。
        //失敗時
        if ($validator->fails()) {
            return redirect(route('tasks.create'))
                ->withErrors($validator)
                ->withInput();
        }
        //成功時
        $task = new Task();
        $task->fill($request->all())->save(); 
        //ユーザが送った全データを取得 → $fillable に定義されたキーだけをモデルに代入 →　データベースに保存
        //$request->all()がなんでも持ってきたやつを、$fillableがフィルタリングして安全に使う。

        return redirect(route('tasks.index'))->with('success', 'タスクを作成しました！');
    }

    public function show($id) //特定のタスクの詳細表示のメソッド
    {
        $task = Task::findOrFail($id); //IDが存在しない場合はページを表示しない
        return view('tasks.show', compact('task')); //（resources/views/tasks/show.blade.php）を返す。
    }

    public function edit($id) //編集フォームを表示。
    {
        $task = Task::findOrFail($id);
        $users = User::all(); // ユーザー一覧を取得
        return view('tasks.create', compact('task', 'users'));
    }

    public function update(Request $request, $id)//編集された内容を保存。
    {
        $validator = $this->validateTask($request);

        if ($validator->fails()) {
            return redirect(route('tasks.create', $id))
                ->withErrors($validator)
                ->withInput();
        }

        $task = Task::findOrFail($id);
        $task->fill($request->all())->save();

        return redirect(route('tasks.index'))->with('success', 'タスクを更新しました！');
    }

    public function destroy($id)  //タスクを削除
    {
        $task = Task::findOrFail($id);
        $task->delete(); 

        return redirect(route('tasks.index'))->with('success', 'タスクを削除しました！');
    }

    protected function validateTask(Request $request)
    {
        $rules = [
        'title' => 'required|string|max:100',
        'content' => 'required|string|max:1000',
        'deadline_at' => 'required|date',
        'support_at' => 'nullable|date',
        'priority' => 'required|integer|in:' . implode(',', array_keys(config('const.task.priority'))),
        'status' => 'required|integer|in:' . implode(',', array_keys(config('const.task.status'))),
        'user_id' => 'required|exists:users,id', //空でないか、かつusersテーブルに存在するか。
    ];//configフォルダのconst.phpのtaskのpriorityとstatusを参照。キーだけを取り出す関数（数字の部分）。
     //inでpriorityとstatusのキーに含まれているかを判断。

    $messages = [
        'title.required' => 'タイトルは必須です。',
        'title.max' => 'タイトルは100文字以内で入力してください。',
        'content.required' => '内容は必須です。',
        'content.max' => '内容は1000文字以内で入力してください。',
        'deadline_at.required' => '対応期限は必須です。',
        'deadline_at.date' => '対応期限は正しい日時形式で入力してください。',
        'support_at.date' => '対応日時は正しい日時形式で入力してください。',
        'priority.required' => '優先度は必須です。',
        'priority.in' => '優先度の値が不正です。',
        'status.required' => 'ステータスは必須です。',
        'status.in' => 'ステータスの値が不正です。',
        'user_id.required' => '担当者は必須です。',
        'user_id.exists' => '選択された担当者が存在しません。',
    ];

    $attributes = [
        'title' => 'タイトル',
        'content' => '内容',
        'deadline_at' => '対応期限',
        'support_at' => '対応日時',
        'priority' => '優先度',
        'status' => 'ステータス',
        'user_id' => '担当者',
    ];


        return Validator::make($request->all(), $rules, $messages, $attributes);
    }
}