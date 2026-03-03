@extends('layouts.app')

@section('title', '注文内容確認')

@section('content')

<div class="w-full max-w-lg mx-auto bg-white p-4 sm:p-6 rounded-xl shadow">

    <h2 class="text-xl font-bold mb-4 text-center">
        注文内容の確認
    </h2>

    {{-- 決済へ進むフォーム --}}
    <form method="POST" action="{{ route('checkout.payment') }}">
        @csrf

        {{-- hiddenでデータ保持 --}}
        <input type="hidden" name="name" value="{{ $name }}">
        <input type="hidden" name="address" value="{{ $address }}">
        <input type="hidden" name="phone" value="{{ $phone }}">
        <input type="hidden" name="payment_method" value="{{ $payment_method }}">

        {{-- 購入者情報 --}}
        <div class="space-y-3 text-sm">

            <div>
                <p class="text-gray-500">名前</p>
                <p class="font-semibold">{{ $name }}</p>
            </div>

            <div>
                <p class="text-gray-500">住所</p>
                <p class="font-semibold">{{ $address }}</p>
            </div>

            <div>
                <p class="text-gray-500">電話番号</p>
                <p class="font-semibold">{{ $phone }}</p>
            </div>

        </div>

        {{-- 商品一覧 --}}
        <div class="mt-6 border-t pt-4">

            <h3 class="font-bold text-sm mb-3">
                注文商品
            </h3>

            @php $total = 0; @endphp

            <div class="space-y-3 text-sm">

                @foreach($cart_items as $item)

                    @continue(!$item->storage)

                    @php
                        $price = $item->storage->price;
                        $subtotal = $price * $item->quantity;
                        $total += $subtotal;
                    @endphp

                    <div class="flex flex-col sm:flex-row sm:justify-between gap-2 border-b pb-2">

                        <div>
                            <p class="font-semibold">
                                {{ $item->storage->name }}
                            </p>

                            <p class="text-gray-500 text-xs">
                                ¥{{ number_format($price) }} × {{ $item->quantity }}
                            </p>
                        </div>

                        <div class="font-bold">
                            ¥{{ number_format($subtotal) }}
                        </div>

                    </div>

                @endforeach

            </div>

        </div>

        {{-- 合計 --}}
        <div class="mt-4 border-t pt-4 text-right">

            <p class="text-gray-500 text-sm">
                合計金額
            </p>

            <p class="text-2xl font-bold">
                ¥{{ number_format($total) }}
            </p>

        </div>

        {{-- 支払い方法 --}}
        <div class="mt-6 border-t pt-4">

<h3>支払い方法</h3>
<p>
    @switch($payment_method)
        @case('credit_card')
            クレジットカード
            @break
        @case('cash')
            コンビニ支払い
            @break
        @case('bank')
            銀行振込
            @break
        @default
            未選択
    @endswitch
</p>

@if ($payment_method === 'credit_card')
    <p class="mt-2 text-sm text-gray-500">
        カード情報は次の画面で安全に入力します。
    </p>
@endif


        </div>

        {{-- ボタン --}}
<div class="mt-6 flex flex-col-reverse sm:flex-row gap-3">

    <button type="submit"
        class="flex-[3] bg-blue-500 text-white py-2 rounded-lg">
        決済を確定する
    </button>

<a href="{{ route('checkout.index') }}"
   class="flex-[1] text-center bg-gray-200 py-2 rounded-lg">
    戻る
</a>


<script>
    // ブラウザのスクロール復元を無効
    if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }

    // ページ表示時にトップへ
    window.onload = function () {
        window.scrollTo(0, 0);
    };
</script>

</div>

    </form>

</div>

@endsection
