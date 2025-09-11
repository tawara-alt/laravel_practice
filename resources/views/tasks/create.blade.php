<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($task) ? 'タスク編集（ID: ' . $task->id . '）' : '新規タスク作成' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">
                        {{ isset($task) ? 'タスク編集' : '新規タスク作成' }}
                    </h1>

                    {{-- バリデーションエラーメッセージの表示 --}}
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded relative" role="alert">
                            <strong class="font-bold">入力内容にエラーがあります！</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                     <!--編集モードか新規作成モード -->
                    <form action="{{ isset($task) ? route('tasks.update', $task->id) : route('tasks.store') }}" method="POST">
                        @csrf
                        @if (isset($task))
                            @method('PUT')
                        @endif

                        {{-- タイトル --}}
                        <div class="mb-4">
                            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">タイトル：</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $task->title ?? '') }}"
                                
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        {{-- 内容 --}}
                        <div class="mb-4">
                            <label for="content" class="block text-gray-700 text-sm font-bold mb-2">内容：</label>
                            <textarea name="content" id="content" rows="6"
                                
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('content', $task->content ?? '') }}</textarea>
                        </div>

{{-- 担当者 --}} <!-- タスクに対して、「誰が担当か」を明確に設定 -->
<div class="mb-4">
    <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">担当者：</label>
    <select name="user_id" id="user_id"
        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        <option value="">-- 担当者を選択 --</option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}"{{ old('user_id', $task->user_id ?? '') == $user->id ? 'selected' : '' }}>
                {{ $user->name }}
            </option>
        @endforeach
    </select>
    @error('user_id')
        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
    @enderror
</div>

                        {{-- 対応期限 --}}
                        <div class="mb-4">

                            <label for="deadline_at" class="block text-gray-700 text-sm font-bold mb-2">対応期限：</label>
                            <input type="datetime-local" name="deadline_at" id="deadline_at" value="{{ old('deadline_at', isset($task->deadline_at) ? \Carbon\Carbon::parse($task->deadline_at)->format('Y-m-d\TH:i') : '') }}"
                                
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                
                        </div>

                        {{-- 対応日時（任意） --}}
                        <div class="mb-4">
                            <label for="support_at" class="block text-gray-700 text-sm font-bold mb-2">対応日時：</label>
                            <input type="datetime-local" name="support_at" id="support_at" value="{{ old('support_at', isset($task->support_at) ? \Carbon\Carbon::parse($task->support_at)->format('Y-m-d\TH:i') : '') }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        {{-- 優先度 --}}
                        <div class="mb-4">
                            <label for="priority" class="block text-gray-700 text-sm font-bold mb-2">優先度：</label>
                            <select name="priority" id="priority"
                                
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                
                                @foreach (config('const.task.priority') as $key => $label) <!--1のときは極高などなど -->
                                    <option value="{{ $key }}" {{ old('priority', $task->priority ?? '') == $key ? 'selected' : '' }}>
                                        {{ $label }} <!--1のときは極高などなど -->
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        

                        {{-- ステータス --}}
                        <div class="mb-4">
                            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">ステータス：</label>
                            <select name="status" id="status"
                                
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                @foreach (config('const.task.status') as $key => $label) <!--1のときは起票などなど -->
                                    <option value="{{ $key }}" {{ old('status', $task->status ?? '') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 送信ボタン --}}
                        <div class="flex items-center justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                {{ isset($task) ? '更新' : '作成' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>