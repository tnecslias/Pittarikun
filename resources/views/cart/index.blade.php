@extends('layouts.app')
@section('title', 'カート')

@section('content')
<div class="flex flex-col justify-center items-center bg-gray-50">

    <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">
        カート
    </h2>

    {{-- ▼ カード表示（お気に入りと合わせる） --}}
    <div class="mt-4 grid gap-6 grid-cols-2 sm:grid-cols-3 md:grid-cols-4">

        @forelse($cart_items as $item)

            @php
                $s = $item->storage; // ストレージ情報
            @endphp

            @continue(!$s)

            <div class="bg-white rounded-xl shadow hover:shadow-xl transition p-4 flex flex-col items-center">

                {{-- 正方形画像 --}}
                <div class="w-full aspect-square bg-gray-100 rounded-lg overflow-hidden">
                    <img src="{{ asset('storage/'.$s->image) }}"
                         alt="{{ $s->name }}"
                         class="object-cover w-full h-full">
                </div>

                {{-- 名前 --}}
                <h3 class="mt-3 text-sm font-bold text-gray-800 text-center">
                    {{ $s->name }}
                </h3>

                {{-- 値段 --}}
                <p class="text-gray-600 text-xs text-center">
                    ¥{{ number_format($s->price) }}
                </p>

                {{-- 数量 --}}
                <p class="text-gray-800 text-sm font-semibold mt-1">
                    数量：{{ $item->quantity }}
                </p>

                {{-- ▼ シンプルデザインのボタン（お気に入りと同じ） --}}
                <div class="mt-3 w-full">

                    {{-- 削除する --}}
                    <form method="POST" action="{{ route('cart.remove', $s->id) }}" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full bg-red-500 hover:bg-red-600 text-white text-sm py-1.5 rounded">
                            削除する
                        </button>
                    </form>

                </div>

            </div>

        @empty
            <p class="col-span-full text-center text-gray-500">
                カートは空です。
            </p>
        @endforelse

    </div>

</div>
@endsection
