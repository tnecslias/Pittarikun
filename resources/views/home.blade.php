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
    @isset($storages)

        <div class="mt-20 grid gap-6 grid-cols-2 sm:grid-cols-3 md:grid-cols-4">

            @forelse($storages as $s)
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

                {{-- ボタン２つ --}}
<div class="mt-3 flex justify-between w-full">

    {{-- いいね --}}
    <form method="POST" action="{{ route('favorites.add', $s->id) }}">
        @csrf
        <button
            class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-100 transition"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6 text-gray-700">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.93 0-3.602 1.126-4.312 2.733C11.29 4.876 9.618 3.75 7.688 3.75 5.099 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
            </svg>
        </button>
    </form>

    {{-- カート --}}
    <form method="POST" action="{{ route('cart.add', $s->id) }}">
        @csrf
        <button
            class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-100 transition ml-2"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6 text-gray-700">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25h9.75m-9.75 0L6 6.75m1.5 7.5l1.125-6.75m9.75 6.75L18 6.75m1.5 7.5l-1.125-6.75M6 6.75h12m-12 0L5.25 4.5m12.75 2.25L18.75 4.5M9 18a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm9 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
            </svg>
        </button>
    </form>

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
