@extends('layouts.app')
@section('title', 'カート')

@section('content')
<div class="flex flex-col justify-center items-center bg-gray-50 py-6">

    <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">
        カート
    </h2>

    <div class="mt-4 grid gap-6 grid-cols-2 sm:grid-cols-3 md:grid-cols-4">

        @forelse($cart_items as $item)

            @php
                $s = $item->storage;
            @endphp

            @continue(!$s)

            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition p-4 flex flex-col items-center border border-gray-100">

                {{-- 画像（正方形 + グラデーション） --}}
                <div class="w-full aspect-square relative rounded-xl overflow-hidden shadow-sm">
                    <img src="{{ asset('storage/'.$s->image) }}"
                         alt="{{ $s->name }}"
                         class="object-cover w-full h-full">

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

                {{-- ▼ 数量変更（＋ / −） --}}
                <div class="flex items-center justify-center mt-4 gap-3">

                    {{-- 数量 -1 --}}
                    <form method="POST" action="{{ route('cart.decrease', $s->id) }}">
                        @csrf
                        <button type="submit"
                            class="w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded-full text-lg font-bold shadow">
                            −
                        </button>
                    </form>

                    <span class="text-sm font-semibold w-6 text-center">
                        {{ $item->quantity }}
                    </span>

                    {{-- 数量 +1 --}}
                    <form method="POST" action="{{ route('cart.increase', $s->id) }}">
                        @csrf
                        <button type="submit"
                            class="w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded-full text-lg font-bold shadow">
                            ＋
                        </button>
                    </form>

                </div>

                {{-- 削除ボタン --}}
                <form method="POST" action="{{ route('cart.remove', $s->id) }}" class="mt-4 w-full">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full bg-red-500 hover:bg-red-600 text-white text-sm py-2 rounded-xl shadow">
                        削除する
                    </button>
                </form>

            </div>

        @empty
            <p class="col-span-full text-center text-gray-500">
                カートは空です。
            </p>
        @endforelse

    </div>

</div>
@endsection
