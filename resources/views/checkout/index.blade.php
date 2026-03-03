@extends('layouts.app')

@section('title', '注文内容確認')

@section('content')
<div class="w-full max-w-lg mx-auto bg-white p-4 sm:p-6 rounded-xl shadow">

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
                    value="{{ old('name', $name) }}"
                    class="w-full border rounded-lg px-3 py-2 text-sm"
                    required>
            </div>

            <div>
                <label class="block text-xs text-gray-600 mb-1">住所</label>
                <input type="text" name="address"
                    value="{{ old('address', $address) }}"
                    class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-xs text-gray-600 mb-1">電話番号</label>
                <input type="text" name="phone"
                    value="{{ old('phone', $phone) }}"
                    class="w-full border rounded-lg px-3 py-2 text-sm">
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

                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 border-b pb-2">
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
    クレジットカード
</label>
<div id="creditCardNotice" class="mt-4 hidden mb-5">
    @php
        $stripeSecret = (string) config('services.stripe.secret', '');
        $isStripeTestMode = str_starts_with($stripeSecret, 'sk_test_');
        $canBypassAnyCard = (bool) config('services.stripe.allow_any_card', false) && app()->environment(['local', 'testing']);
    @endphp

    @if (!$canBypassAnyCard && $isStripeTestMode)
        <p class="mb-3 rounded-lg bg-amber-50 text-amber-800 text-xs px-3 py-2">
            この環境では Stripe テストカードを使用してください（例: 4242 4242 4242 4242 / 有効期限は未来日 / CVCは任意3桁）。
        </p>
    @elseif (!$canBypassAnyCard)
        <p class="mb-3 rounded-lg bg-gray-50 text-gray-700 text-xs px-3 py-2">
            カード情報を入力してください。
        </p>
    @endif

    <div class="space-y-3">
        <div>
            <label class="block text-xs text-gray-600 mb-1">
                カード番号
            </label>
            <input type="text"
                name="card_number"
                value="{{ old('card_number') }}"
                placeholder="4242 4242 4242 4242"
                class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>

        <div class="flex gap-3">
            <div class="flex-1">
                <label class="block text-xs text-gray-600 mb-1">
                    有効期限
                </label>
                <input type="text"
                    name="card_expiry"
                    value="{{ old('card_expiry') }}"
                    placeholder="MM/YY"
                    inputmode="numeric"
                    maxlength="5"
                    class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>

            <div class="flex-1">
                <label class="block text-xs text-gray-600 mb-1">
                    CVC
                </label>
                <input type="text"
                    name="card_cvc"
                    value="{{ old('card_cvc') }}"
                    placeholder="123"
                    class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
        </div>
    </div>
</div>


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
        <div class="mt-6 flex flex-col-reverse sm:flex-row gap-3">
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
<script>

function toggleCreditCardForm() {

    const selected = document.querySelector('input[name="payment_method"]:checked');
    const form = document.getElementById('creditCardNotice');

    if (selected && selected.value === 'credit_card') {
        form.classList.remove('hidden');
    } else {
        form.classList.add('hidden');
    }

}

// ラジオ変更時
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', toggleCreditCardForm);
});

const cardExpiryInput = document.querySelector('input[name="card_expiry"]');
if (cardExpiryInput) {
    cardExpiryInput.addEventListener('input', (event) => {
        const digits = event.target.value.replace(/\D/g, '').slice(0, 4);

        if (digits.length <= 2) {
            event.target.value = digits;
            return;
        }

        event.target.value = `${digits.slice(0, 2)}/${digits.slice(2)}`;
    });
}

// 初期表示時
window.addEventListener('load', toggleCreditCardForm);

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
@endsection
