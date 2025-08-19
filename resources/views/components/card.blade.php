{{-- resources/views/components/card.blade.php --}}
<div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;"> {{-- 簡単な枠 --}}
    {{-- コントローラーやコンポーネント呼び出し元から渡された変数 $title があればここに表示 --}}
    <div style="font-weight: bold;">{{ $title }}</div>

    {{-- コンポーネントのタグで囲まれた内容（スロット）があればここに表示 --}}
    <div style="margin-top: 5px;">{{ $slot }}</div>
</div>