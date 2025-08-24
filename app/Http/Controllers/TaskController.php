<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index() 
    {
        $tasks = Task::all(); //データベースから全タスクを取得
        return view('tasks.index', compact('tasks')); //一覧ページに表示
    }

    public function create() //新規作成フォームを表示。
    {
        return view('tasks.create'); //（resources/views/tasks/create.blade.php）を返す。
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
        $task = Task::findOrFail($id); //404エラー
        return view('tasks.create', compact('task'));
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
    ];

    $attributes = [
        'title' => 'タイトル',
        'content' => '内容',
        'deadline_at' => '対応期限',
        'support_at' => '対応日時',
        'priority' => '優先度',
        'status' => 'ステータス',
    ];


        return Validator::make($request->all(), $rules, $messages, $attributes);
    }
}