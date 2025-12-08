@extends('layouts.app')

@section('title', 'お気に入り一覧')

@section('content')
<div class="flex flex-col justify-center items-center bg-gray-50">

    <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">
        お気に入り一覧
    </h2>

    {{-- ▼ カード表示 --}}
    <div class="mt-4 grid gap-6 grid-cols-2 sm:grid-cols-3 md:grid-cols-4">

        @forelse($favorites as $fav)

            {{-- ★ Favorite → Storage を取得 --}}
            @php
                $s = $fav->storage; 
            @endphp

            {{-- storage が存在しなければスキップ --}}
            @continue(!$s)

            {{-- ★ isFavorited（常に true）--}}
            @php
                $isFavorited = true;
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

                {{-- ▼ いいね & カート --}}
                <div class="mt-3 flex justify-between w-full">

                    {{-- ❤️ お気に入り解除 --}}
                    <form method="POST" action="{{ route('favorite.toggle', $s->id) }}">
                        @csrf
                        <button type="submit" class="favorite-btn text-xl">
                            🗑️
                        </button>
                    </form>

                    {{-- 🛒 カート --}}
                    <form method="POST" action="{{ route('cart.add', $s->id) }}">
                        @csrf
                        <button type="submit" class="cart-btn">🛒</button>
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
