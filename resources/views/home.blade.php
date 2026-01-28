@extends('layouts.app')

@section('title', 'ホーム')

@section('content')
<div class="flex flex-col justify-center items-center bg-gray-50">

    {{-- タイトル --}}
    <h2 class="text-2xl font-bold mb-4 text-gray-800 text-center">
        収納スペースの大きさを入力してください
    </h2>

    {{-- ========= ① 入力フォーム（白い枠） ========= --}}
    <div class="w-full max-w-lg bg-white shadow-lg rounded-xl p-8">

        {{-- 検索フォーム --}}
        <form method="GET" action="{{ route('storage.search') }}" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium text-gray-700">幅 (cm)</label>
                <input type="number" name="width" placeholder="例: 100"
                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">高さ (cm)</label>
                <input type="number" name="height" placeholder="例: 200"
                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">奥行き (cm)</label>
                <input type="number" name="depth" placeholder="例: 50"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
            </div>

            <button type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-lg shadow">
                検索
            </button>
        </form>

    </div> 

    {{-- ここから下に検索結果を出す --}}

@isset($storages)

    <div class="mt-20 grid gap-6 grid-cols-2 sm:grid-cols-3 md:grid-cols-4">

        @forelse($storages as $s)

            {{-- ★ ここで isFavorited を安全に計算する --}}
            @php
                $isFavorited = false;
                if (auth()->check()) {
                    $favorites = auth()->user()->favorites()->pluck('storage_id');
                    $isFavorited = $favorites->contains($s->id);
                }
            @endphp

            <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 p-4 flex flex-col items-center border border-gray-100">

    {{-- 画像枠 --}}
    <div class="w-full aspect-square relative rounded-xl overflow-hidden shadow-sm">

        {{-- 画像 --}}
        <img src="{{ asset('storage/'.$s->image) }}"
             alt="{{ $s->name }}"
             class="object-cover w-full h-full">

        {{-- 上部グラデーション（高級感UP） --}}
        <div class="absolute inset-0 bg-gradient-to-b from-black/10 to-transparent"></div>

{{-- お気に入り（ハート） --}}
<div class="absolute bottom-2 right-2">

    @auth
        {{-- ===== ログイン時 ===== --}}
        <form method="POST" action="{{ route('favorite.toggle', $s->id) }}">
            @csrf
            <button type="submit"
                class="flex items-center justify-center w-10 h-10 bg-white/80 backdrop-blur-md border border-gray-200 rounded-full shadow hover:scale-110 transition">
                @if($isFavorited)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#e63946" viewBox="0 0 24 24" class="w-6 h-6">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5
                        2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09
                        C13.09 3.81 14.76 3 16.5 3 19.58 3
                        22 5.42 22 8.5c0 3.78-3.4
                        6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#ccc" viewBox="0 0 24 24" class="w-6 h-6">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5
                        2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09
                        C13.09 3.81 14.76 3 16.5 3 19.58 3
                        22 5.42 22 8.5c0 3.78-3.4
                        6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                @endif
            </button>
        </form>
    @endauth

    @guest
        {{-- ===== 未ログイン時（ぼかし） ===== --}}
        <div class="relative">

            <div class="blur-sm opacity-60 pointer-events-none">
                <button
                    class="flex items-center justify-center w-10 h-10 bg-white/80 backdrop-blur-md border border-gray-200 rounded-full shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#ccc" viewBox="0 0 24 24" class="w-6 h-6">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5
                        2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09
                        C13.09 3.81 14.76 3 16.5 3 19.58 3
                        22 5.42 22 8.5c0 3.78-3.4
                        6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </button>
            </div>

        </div>
    @endguest

</div>


                    

    </div>

    {{-- テキスト類 --}}
    <h3 class="mt-4 text-base font-bold text-gray-800 text-center">
        {{ $s->name }}
    </h3>

    <p class="text-gray-600 text-sm text-center">
        ¥{{ number_format($s->price) }}
    </p>


    {{-- ボタン・数量 --}}
<div class="mt-4 w-full space-y-3 relative">

    @auth
        {{-- ===== ログイン時（通常操作） ===== --}}
        <select name="quantity"
            class="border rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-400 w-full">
            @for ($i = 1; $i <= 10; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>

        <form method="POST" action="{{ route('cart.add', $s->id) }}">
            @csrf
            <button type="submit"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white text-sm py-2 rounded-lg shadow">
                カートに追加
            </button>
        </form>

    @endauth


@guest
<div class="relative w-full">

    {{-- ===== 未ログイン時（ぼかし表示） ===== --}}
    <div class="space-y-3 blur-sm opacity-60 pointer-events-none">

        <select
            class="border rounded-lg px-3 py-1.5 text-sm w-full">
            <option>1</option>
        </select>

        <button
            class="w-full bg-blue-400 text-white text-sm py-2 rounded-lg shadow">
            カートに追加
        </button>

    </div>

{{-- ===== ぼかしの上に重ねるメッセージ ===== --}}
<div class="absolute inset-0 flex items-center justify-center">
    <p class="text-xs text-gray-600 text-center">
        <a href="{{ route('login') }}"
           class="text-blue-600 underline cursor-pointer hover:text-blue-800">
            ログイン
        </a>
        するとお気に入り・カート追加ができます
    </p>
</div>


</div>
@endguest


</div>


</div>

        @empty
            <p class="col-span-full text-center text-gray-500">
                条件に合う収納ケースはありません。
            </p>
        @endforelse

    </div>
@endisset




    {{-- ========= ③ 候補カード（正方形） ========= --}}
    @if(isset($results))
        <h3 class="text-xl font-bold mt-12 text-center">入れられる組み合わせ候補</h3>

        <div class="mt-6 grid gap-6 grid-cols-2 sm:grid-cols-3 md:grid-cols-4">
            @forelse($results as $r)
            <div class="bg-white rounded-xl shadow hover:shadow-xl transition p-4 flex flex-col items-center">

                <div class="w-full aspect-square bg-gray-100 rounded-lg overflow-hidden">
                    <img src="{{ asset('storage/'.$r['case']->image) }}"
                         alt="{{ $r['case']->name }}"
                         class="object-cover w-full h-full">
                </div>

                <h4 class="mt-3 text-sm font-bold text-gray-800 text-center">
                    {{ $r['case']->name }}
                </h4>

                <p class="text-gray-600 text-xs">パターン: {{ $r['pattern'] }} cm</p>
                <p class="text-gray-600 text-xs">配置: {{ $r['layout'] }}</p>

                <p class="text-gray-800 font-bold text-lg mt-2">
                    {{ $r['count'] }} 個
                </p>

            </div>
            @empty
                <p class="col-span-full text-center text-gray-500">
                    条件に合う組み合わせはありません。
                </p>
            @endforelse
        </div>
    @endif

</div>
@endsection
