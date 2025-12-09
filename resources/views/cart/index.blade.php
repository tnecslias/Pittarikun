@extends('layouts.app')
@section('title', 'カート')

@section('content')
<div class="flex flex-col justify-center items-center bg-gray-50">

    <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">
        カート
    </h2>

    <div class="mt-4 grid gap-6 grid-cols-2 sm:grid-cols-3 md:grid-cols-4">

        @forelse($cart_items as $item)

            @php
                $s = $item->storage;
            @endphp

            @continue(!$s)

            <div class="bg-white rounded-xl shadow hover:shadow-xl transition p-4 flex flex-col items-center">

                {{-- 正方形画像 --}}
                <div class="w-full aspect-square bg-gray-100 rounded-lg overflow-hidden">
                    <img src="{{ asset('storage/'.$s->image) }}"
                         alt="{{ $s->name }}"
                         class="object-cover w-full h-full">
                </div>

                {{-- 商品名 --}}
                <h3 class="mt-3 text-sm font-bold text-gray-800 text-center">
                    {{ $s->name }}
                </h3>

                {{-- 値段 --}}
                <p class="text-gray-600 text-xs text-center">
                    ¥{{ number_format($s->price) }}
                </p>

                {{-- ▼ 数量変更（＋ / −） --}}
                <div class="flex items-center justify-center mt-3 gap-2">

                    {{-- 数量 -1 --}}
                    <form method="POST" action="{{ route('cart.decrease', $s->id) }}">
                        @csrf
                        <button type="submit"
                            class="px-2 py-1 bg-gray-300 hover:bg-gray-400 rounded text-sm font-bold">
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
                            class="px-2 py-1 bg-gray-300 hover:bg-gray-400 rounded text-sm font-bold">
                            ＋
                        </button>
                    </form>
                </div>

                {{-- 削除 --}}
                <form method="POST" action="{{ route('cart.remove', $s->id) }}" class="mt-3 w-full">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full bg-red-500 hover:bg-red-600 text-white text-sm py-1.5 rounded">
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
