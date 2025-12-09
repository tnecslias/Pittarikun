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

            @php
                $s = $fav->storage; 
            @endphp

            @continue(!$s)

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

                {{-- ▼ シンプルデザインの2ボタン --}}
                <div class="mt-3 flex gap-2 w-full">

                    {{-- 削除する --}}
                    <form method="POST" action="{{ route('favorite.toggle', $s->id) }}" class="w-1/2">
                        @csrf
                        <button type="submit"
                                class="w-full bg-red-500 hover:bg-red-600 text-white text-sm py-1.5 rounded">
                            削除する
                        </button>
                    </form>

                    {{-- カートに追加 --}}
                    <form method="POST" action="{{ route('cart.add', $s->id) }}" class="w-1/2">
                        @csrf
                        <button type="submit"
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white text-sm py-1.5 rounded">
                            カートに追加
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
