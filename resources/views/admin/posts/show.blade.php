<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            記事詳細（ID: {{ $post->id }}）
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">{{ $post->title }}</h3>
                    <p class="mb-2"><strong>公開日時:</strong> {{ $post->published_at ? (new \Carbon\Carbon($post->published_at))->format('Y-m-d H:i:s') : '未定' }}</p>
                    <div class="mt-4">
                        <strong>本文:</strong>
                        <p class="mt-2 whitespace-pre-line">{{ $post->body }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

