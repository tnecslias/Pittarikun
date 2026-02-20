@extends('layouts.app')

@section('title', '決済処理中')

@section('content')

<div class="w-full max-w-lg mx-auto bg-white p-4 sm:p-6 rounded-xl shadow text-center">

    {{-- アイコン切り替え --}}
    <div class="text-5xl mb-4 animate-pulse">
        @switch($payment_method)
            @case('credit_card')
                💳
                @break

            @case('cash')
                🏪
                @break

            @case('bank')
                🏦
                @break

            @default
                💰
        @endswitch
    </div>

    <h2 class="text-xl font-bold mb-2">
        決済処理中...
    </h2>

    {{-- メッセージ切り替え --}}
    <p id="paymentMessage" class="text-gray-600">
        @switch($payment_method)

            @case('credit_card')
                カード会社に接続しています...
                @break

            @case('cash')
                コンビニ決済を準備しています...
                @break

            @case('bank')
                銀行振込情報を準備しています...
                @break

            @default
                決済を処理しています...
        @endswitch
    </p>

    <p id="paymentError" class="text-red-600 text-sm mt-3 hidden"></p>

    <div class="w-full bg-gray-200 rounded-full h-2 mt-4">
        <div class="bg-blue-500 h-2 rounded-full animate-pulse"
             style="width: 100%">
        </div>
    </div>

</div>

<form id="completeForm" method="POST" action="{{ route('checkout.complete') }}">
    @csrf
</form>

<script>
const paymentMethod = @json($payment_method);
const paymentError = document.getElementById('paymentError');
const paymentMessage = document.getElementById('paymentMessage');

function submitComplete() {
    document.getElementById('completeForm').submit();
}

async function chargeStripe() {
    try {
        const response = await fetch(@json(route('stripe.charge')), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': @json(csrf_token()),
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                payment_method: paymentMethod,
                card_number: @json($card_number),
                card_expiry: @json($card_expiry),
                card_cvc: @json($card_cvc),
            }),
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || '決済に失敗しました。');
        }

        if (result.bypassed) {
            paymentMessage.textContent = '開発モード: 決済をスキップして注文を確定します...';
        }

        setTimeout(submitComplete, 800);
    } catch (error) {
        paymentError.textContent = error.message;
        paymentError.classList.remove('hidden');
        paymentMessage.textContent = 'カード決済に失敗しました。内容を確認してください。';
    }
}

if (paymentMethod === 'credit_card') {
    chargeStripe();
} else {
    setTimeout(submitComplete, 1200);
}
</script>

@endsection
