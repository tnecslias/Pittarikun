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

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 mb-4 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

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


    {{-- ========= ② 検索結果カード（正方形） ========= --}}
    {{-- ========= ② 検索結果カード（正方形） ========= --}}
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

            <div class="bg-white rounded-xl shadow hover:shadow-xl transition p-4 flex flex-col items-center">

                {{-- 正方形画像 --}}
                <div class="w-full aspect-square bg-gray-100 rounded-lg overflow-hidden">
                    <img src="{{ asset('storage/'.$s->image) }}"
                         alt="{{ $s->name }}"
                         class="object-cover w-full h-full">
                </div>

                <h3 class="mt-3 text-sm font-bold text-gray-800 text-center">
                    {{ $s->name }}
                </h3>

                <p class="text-gray-600 text-xs text-center">
                    ¥{{ number_format($s->price) }}
                </p>

                {{-- ▼ いいね & カート ボタン --}}
                <div class="mt-3 flex justify-between w-full">

                    {{-- いいねボタン --}}
                    @auth
                        <form method="POST" action="{{ route('favorite.toggle', $s->id) }}">
                            @csrf
                            <button type="submit" class="favorite-btn">
                                ❤️
                            </button>
                        </form>

                    @else
                        <a href="{{ route('login') }}" class="favorite-btn">
                            ❤️
                        </a>
                    @endauth


                    {{-- カートボタン --}}
                    @auth
                        <form method="POST" action="{{ route('cart.add', $s->id) }}">
                            @csrf
                            <button type="submit" class="cart-btn">🛒</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="cart-btn">🛒</a>
                    @endauth

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
