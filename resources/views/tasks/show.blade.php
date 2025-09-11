<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            タスク詳細（ID: {{ $task->id }})
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">{{ $task->title }}</h3>

                    <p class="mb-2"><strong>担当者:</strong>
                        {{ $task->user->name ?? '未設定' }}
                    </p>

                    <p class="mb-2"><strong>対応期限:</strong>
                        <!-- $task->deadline_at に値があるかどうかを判定し、あれば Carbon で整形して表示 -->
                        {{ $task->deadline_at ? \Carbon\Carbon::parse($task->deadline_at)->format('Y-m-d H:i') : '未定' }}
                    </p>

                    <p class="mb-2"><strong>優先度:</strong>
                        <!-- configフォルダのconst.phpのtaskのpriorityがあればそのまま表示 -->
                        {{ config('const.task.priority')[$task->priority] ?? '未定義' }}
                    </p>

                    <p class="mb-2"><strong>ステータス:</strong>
                        <!-- configフォルダのconst.phpのtaskのstatusがあればそのまま表示 -->
                        {{ config('const.task.status')[$task->status] ?? '未定義' }}
                    </p>

                    <p class="mb-2"><strong>最終更新日時:</strong>
                        <!-- Bladeテンプレートの構文で、Carbon クラスを使って日付を整形。 -->
                        {{ \Carbon\Carbon::parse($task->updated_at)->format('Y-m-d H:i') }}
                    </p>

                    @if (!empty($task->content)) <!-- 内容が空でなければ表示 -->
                        <div class="mt-4">
                            <strong>詳細説明:</strong>
                            <p class="mt-2 whitespace-pre-line">{{ $task->content }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>