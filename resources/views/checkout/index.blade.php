@extends('layouts.app')

@section('title', '注文内容確認')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded-xl shadow">

    <h2 class="text-xl font-bold mb-4 text-center">
        注文内容の確認
    </h2>

    {{-- 注文確定用フォーム --}}
    <form method="POST" action="{{ route('checkout.confirm') }}">
        @csrf

        {{-- 名前 --}}
        <div class="space-y-3 mt-4">
            <div>
                <label class="block text-xs text-gray-600 mb-1">名前</label>
                <input type="text" name="name"
                       value="{{ old('name') }}"
                       class="w-full border rounded-lg px-3 py-2 text-sm"
                       required>
            </div>
        </div>

        {{-- 住所・電話番号 --}}
        <div class="space-y-3 mt-4">
            <div>
                <label class="block text-xs text-gray-600 mb-1">住所</label>
                <input type="text" name="address"
                       value="{{ old('address') }}"
                       class="w-full border rounded-lg px-3 py-2 text-sm"
                       required>
            </div>

            <div>
                <label class="block text-xs text-gray-600 mb-1">電話番号</label>
                <input type="text" name="phone"
                       value="{{ old('phone') }}"
                       class="w-full border rounded-lg px-3 py-2 text-sm"
                       required>
            </div>
        </div>

        {{-- 注文商品一覧 --}}
        <div class="mt-6 border-t pt-4">
            <h3 class="font-bold text-sm mb-3">注文商品</h3>

            <div class="space-y-3 text-sm">
                @php $total = 0; @endphp

                @foreach($cart_items as $item)
                    @continue(!$item->storage)

                    @php
                        $price = $item->storage->price;
                        $subtotal = $price * $item->quantity;
                        $total += $subtotal;
                    @endphp

                    <div class="flex justify-between items-center border-b pb-2">
                        <div>
                            <p class="font-semibold">{{ $item->storage->name }}</p>
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
        <div class="mt-4 border-t pt-4 text-sm text-right">
            <p class="text-gray-600">合計金額</p>
            <p class="text-2xl font-bold text-gray-800">
                ¥{{ number_format($total) }}
            </p>
        </div>

        {{-- 支払い方法 --}}
        <div class="mt-6 border-t pt-4">
            <h3 class="font-bold text-sm mb-3">支払い方法</h3>

<label class="flex items-center gap-2">
    <input type="radio" name="payment_method" value="credit_card"
        {{ old('payment_method', $payment_method) == 'credit_card' ? 'checked' : '' }}>
    登録済みクレジットカード
</label>

<label class="flex items-center gap-2">
    <input type="radio" name="payment_method" value="cash"
        {{ old('payment_method', $payment_method) == 'cash' ? 'checked' : '' }}>
    コンビニ支払い　*請求書は後日郵送されます
</label>

<label class="flex items-center gap-2">
    <input type="radio" name="payment_method" value="bank"
        {{ old('payment_method', $payment_method) == 'bank' ? 'checked' : '' }}>
    銀行振込　*振込先は後日郵送されます
</label>

        </div>

        {{-- ボタン --}}
        <div class="mt-6 flex gap-3">
            <button type="submit"
                class="flex-[3] bg-blue-500 text-white py-2 rounded-lg">
                注文内容の確認
            </button>

            <a href="{{ route('cart.index') }}"
               class="flex-[1] text-center bg-gray-200 py-2 rounded-lg">
                戻る
            </a>
        </div>

    </form>

</div>
@endsection
