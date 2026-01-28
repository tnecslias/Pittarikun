@extends('layouts.app')

@section('title', 'お気に入り一覧')

@section('content')
<div class="flex flex-col justify-center items-center bg-gray-50">

    <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">
        お気に入り一覧
    </h2>

    {{-- ▼ カード一覧 --}}
    <div class="mt-4 grid gap-6 grid-cols-2 sm:grid-cols-3 md:grid-cols-4">

        @forelse($favorites as $fav)

            @php
                $s = $fav->storage;
            @endphp

            @continue(!$s)

            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition p-4 flex flex-col items-center border border-gray-100">

                {{-- 画像エリア（正方形 + グラデーション） --}}
                <div class="w-full aspect-square relative rounded-xl overflow-hidden shadow-sm">
                    <img src="{{ asset('storage/'.$s->image) }}"
                         alt="{{ $s->name }}"
                         class="object-cover w-full h-full">

                    {{-- 上部の薄い黒グラデーション --}}
                    <div class="absolute inset-0 bg-gradient-to-b from-black/10 to-transparent"></div>
                </div>

                {{-- 商品名 --}}
                <h3 class="mt-4 text-sm font-bold text-gray-800 text-center line-clamp-2">
                    {{ $s->name }}
                </h3>

                {{-- 価格 --}}
                <p class="text-gray-600 text-xs text-center mt-1">
                    ¥{{ number_format($s->price) }}
                </p>

                {{-- ▼ 2ボタン（削除 / カートに追加） --}}
                <div class="mt-4 flex gap-3 w-full">

                    {{-- お気に入り削除 --}}
                    <form method="POST" action="{{ route('favorite.toggle', $s->id) }}" class="flex-1">
                        @csrf
                        <button type="submit"
                                class="w-full bg-red-500 hover:bg-red-600 text-white text-sm py-2 rounded-xl shadow">
                            削除
                        </button>
                    </form>

                    {{-- カートに追加 --}}
                    <form method="POST" action="{{ route('cart.add', $s->id) }}" class="flex-1">
                        @csrf
                        <button type="submit"
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white text-sm py-2 rounded-xl shadow">
                            カートへ
                        </button>
                    </form>
                </div>

            </div>

        @empty
            <p class="col-span-full text-center text-gray-500">
                お気に入りはありません。
            </p>
        @endforelse

    </div>

</div>
@endsection
