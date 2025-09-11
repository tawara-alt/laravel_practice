<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            タスク一覧
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded relative" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    {{-- 検索エリア --}}
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-6 text-gray-900">
                <form method="GET" action="{{route('tasks.index')}}">
                    <div class="search-form-container" style="display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 20px; padding: 10px; border-bottom: 1px solid #ccc;">
                        <div>
                            <label for="search_title">タイトル:</label>
                            <input type="text" id="search_title" name="title" value="">
                        </div>
                        <div>
                            <label for="search_user_id">担当者:</label>
                            <select id="search_user_id" name="user_id">
                                <option value="">すべて</option>
                                @foreach ($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="border: 1px solid #eee; padding: 5px;">
                            <label>ステータス:</label>
                            <div class="checkbox-group" style="display: flex; flex-direction: column; gap: 5px;">
                                @foreach (config('const.task.status') as $key => $label)
                                <label>
                                    <input type="checkbox" name="status[]" value="{{$key}}" {{ in_array($key, request()->input('status', [])) ? 'checked' : '' }}>
                                    {{ $label }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                        <div style="border: 1px solid #eee; padding: 5px;">
                            <label>優先度:</label>
                            <div class="checkbox-group" style="display: flex; flex-direction: column; gap: 5px;">
                                @foreach (config('const.task.priority') as $key => $label)
                                <label>
                                    <input type="checkbox" name="priority[]" value="{{$key}}" {{ in_array($key, request()->input('priority', [])) ? 'checked' : '' }}>
                                    {{ $label }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                        <div style="flex-basis: 100%;"> 
                            <label for="search_deadline_from">対応期限 (From):</label>
                            <input type="date" id="search_deadline_from" name="deadline_from" value="">
                        </div>
                        <div>
                            <label for="search_deadline_to">対応期限 (To):</label>
                            <input type="date" id="search_deadline_to" name="deadline_to" value="">
                        </div>
                    </div>
                    <div>
                        <button type="submit" style="padding: 8px 15px;">検索</button>
                        <a href="{{route('tasks.index')}}" role="button" style="padding: 8px 15px; text-decoration: none; border: 1px solid #ccc; color: #333;">リセット</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- CSVダウンロードボタン（新規作成ボタンの上に配置） --}}
{{-- 画面一覧と同内容をダウンロードするため、検索条件をhiddenで保持する --}}
<div class="flex items-center justify-end">
  <form method="GET" action="{{ route('tasks.downloadCsv') }}" style="margin-top: 20px;">
        
        <input type="hidden" name="title" value="{{request()->input('title')}}">
        <input type="hidden" name="user_id" value="{{request()->input('user_id')}}">

        {{-- ステータスを選択された分だけループ処理 --}}
        @foreach(request()->input('status',[]) as $status)
        <input type="hidden" name="status[]" value="{{$status}}">
        @endforeach

        {{-- 優先度を選択された分だけループ処理 --}}
        @foreach(request()->input('priority',[]) as $priority)
        <input type="hidden" name="priority[]" value="{{$priority}}">
        @endforeach

        <input type="hidden" name="deadline_from" value="{{request()->input('deadline_from')}}">
        <input type="hidden" name="deadline_to" value="{{request()->input('deadline_to')}}">

        <button type="submit" style="padding: 8px 15px; background-color: #28a745; color: white;">CSVダウンロード</button>
    </form>
</div>




                    <div class="flex justify-end mb-4">
                        <a href="{{ route('tasks.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            新規作成
                        </a>
                    </div><!--/tasks/create というURLにアクセス-->

                    <table class="table-auto w-full border">
                        <thead>
                            <tr>
                                <th class="border px-4 py-2">ID</th>
                                <th class="border px-4 py-2">タイトル</th>
                                <th class="border px-4 py-2">担当者</th>
                                <th class="border px-4 py-2">対応期限</th>
                                <th class="border px-4 py-2">優先度</th>
                                <th class="border px-4 py-2">ステータス</th>
                                <th class="border px-4 py-2">最終更新日時</th>
                                <th class="border px-4 py-2">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($tasks->isEmpty())
                            <tr>
                                <td colspan="8" class="text-center text-gray-500 py-4">
                                    該当するタスクが見つかりませんでした。
                                </td>
                            </tr>
                            @else
                            @foreach ($tasks as $task)
                                <tr>
                                    <td class="border px-4 py-2">{{ $task->id }}</td>
                                    <td class="border px-4 py-2">{{ $task->title }}</td>
                                    <td class="border px-4 py-2">{{ $task->user->name ?? '未設定' }}</td> <!-- userが存在しない場合は未設定-->
                                    <td class="border px-4 py-2">{{ $task->deadline_at ? (new \Carbon\Carbon($task->deadline_at))->format('Y-m-d H:i:s') : '未定' }}</td>
                                    <td class="border px-4 py-2">{{ isset(config('const.task.priority')[$task->priority]) ? config('const.task.priority')[$task->priority] : '未定義' }}</td>
                                    <td class="border px-4 py-2">{{ isset(config('const.task.status')[$task->status]) ? config('const.task.status')[$task->status] : '未定義' }}</td>
                                    <td class="border px-4 py-2">{{ (new \Carbon\Carbon($task->updated_at))->format('Y-m-d H:i:s') }}</td>
                                    
                                    <td class="border px-4 py-2 flex items-center justify-center space-x-2">
                                        <a href="{{ route('tasks.show', $task->id) }}" class="text-blue-600 hover:underline">詳細</a>
                                        <!--/tasks/1 のようなURLにアクセス-->
                                        <a href="{{ route('tasks.edit', $task->id) }}" class="text-green-600 hover:underline">編集</a>
                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline bg-transparent border-none cursor-pointer p-0 m-0">削除</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>