@extends('layouts.app')
@section('title', 'カート')

@section('content')
<div class="flex flex-col justify-center items-center bg-gray-50">

    <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">
        カート
    </h2>


    {{-- 成功メッセージ --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 mb-4 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-6 grid-cols-2 sm:grid-cols-3 md:grid-cols-4 w-full max-w-4xl">

        @forelse($cart_items as $item)

            {{-- 商品情報 --}}
            @php
                $storage = $item->storage;  // ← リレーションで取得
            @endphp

            <div class="bg-white rounded-xl shadow hover:shadow-xl transition p-4 flex flex-col items-center">

                {{-- 正方形画像 --}}
                <div class="w-full aspect-square bg-gray-100 rounded-lg overflow-hidden">
                    <img src="{{ asset('storage/'.$storage->image) }}"
                         alt="{{ $storage->name }}"
                         class="object-cover w-full h-full">
                </div>

                {{-- 名前 --}}
                <h3 class="mt-3 text-sm font-bold text-gray-800 text-center">
                    {{ $storage->name }}
                </h3>

                {{-- 値段 & 個数 --}}
                <p class="text-gray-600 text-xs text-center">
                    ¥{{ number_format($storage->price) }}
                </p>
                <p class="text-gray-800 text-sm font-semibold">
                    数量：{{ $item->quantity }}
                </p>

                {{-- 削除ボタン --}}
                <form method="POST" action="{{ route('cart.remove', $storage->id) }}" class="mt-3 w-full">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="w-full bg-red-500 hover:bg-red-600 text-white text-sm py-1 rounded-lg shadow">
                        削除
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
