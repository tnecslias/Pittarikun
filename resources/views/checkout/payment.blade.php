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

@if ($payment_method === 'credit_card' && !($can_bypass_card_validation ?? false) && !($skip_stripe_payment ?? false))
<div id="stripeCardSection" class="w-full max-w-lg mx-auto mt-4 bg-white p-4 sm:p-6 rounded-xl shadow">
    <p class="text-sm text-gray-700 mb-3">
        セキュリティのため、カード情報はこの画面で再入力してください。
    </p>
    <div id="stripeCardElement" class="border rounded-lg px-3 py-3"></div>
    <p id="stripeCardElementError" class="text-red-600 text-sm mt-3 hidden"></p>
    <button type="button" id="stripePayButton"
        class="w-full mt-4 bg-blue-500 text-white py-2 rounded-lg disabled:opacity-60">
        カード情報を送信して決済する
    </button>
</div>
@endif

<form id="completeForm" method="POST" action="{{ route('checkout.complete') }}">
    @csrf
</form>

@if ($payment_method === 'credit_card' && !($can_bypass_card_validation ?? false) && !($skip_stripe_payment ?? false))
<script src="https://js.stripe.com/v3/"></script>
@endif
<script>
const paymentMethod = @json($payment_method);
const paymentError = document.getElementById('paymentError');
const paymentMessage = document.getElementById('paymentMessage');
const stripePublishableKey = @json($stripe_publishable_key ?? '');
const canBypassCardValidation = @json($can_bypass_card_validation ?? false);
const skipStripePayment = @json($skip_stripe_payment ?? false);
const stripeBillingName = @json($stripe_billing_name ?? '');
const stripeCardElementError = document.getElementById('stripeCardElementError');
const stripePayButton = document.getElementById('stripePayButton');
let stripe = null;
let elements = null;
let cardElement = null;

function submitComplete() {
    document.getElementById('completeForm').submit();
}

function setInlineCardError(message = '') {
    if (!stripeCardElementError) return;
    if (!message) {
        stripeCardElementError.textContent = '';
        stripeCardElementError.classList.add('hidden');
        return;
    }
    stripeCardElementError.textContent = message;
    stripeCardElementError.classList.remove('hidden');
}

function initStripeElements() {
    if (canBypassCardValidation || paymentMethod !== 'credit_card') {
        return;
    }

    if (!stripePublishableKey) {
        throw new Error('STRIPE_PUBLISHABLE_KEY が未設定です。');
    }

    if (!window.Stripe) {
        throw new Error('Stripe.js の読み込みに失敗しました。');
    }

    if (stripe && cardElement) {
        return;
    }

    stripe = window.Stripe(stripePublishableKey);
    elements = stripe.elements();
    cardElement = elements.create('card', {
        hidePostalCode: true,
    });
    cardElement.mount('#stripeCardElement');
    cardElement.on('change', (event) => {
        setInlineCardError(event.error ? event.error.message : '');
    });
}

async function createStripePaymentMethodId() {
    if (canBypassCardValidation) {
        return null;
    }

    initStripeElements();

    const { paymentMethod: createdPaymentMethod, error } = await stripe.createPaymentMethod({
        type: 'card',
        card: cardElement,
        billing_details: stripeBillingName ? { name: stripeBillingName } : undefined,
    });

    if (error) {
        throw new Error(error.message || 'カード情報の送信に失敗しました。');
    }

    return createdPaymentMethod?.id || null;
}

async function chargeStripe(stripePaymentMethodId = null) {
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
                stripe_payment_method_id: stripePaymentMethodId,
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
        throw error;
    }
}

if (paymentMethod === 'credit_card') {
    if (skipStripePayment) {
        setTimeout(submitComplete, 1200);
    } else if (canBypassCardValidation) {
        chargeStripe().catch(() => {});
    } else {
        try {
            initStripeElements();
            paymentMessage.textContent = 'カード情報を入力して決済を実行してください。';
        } catch (error) {
            paymentError.textContent = error.message;
            paymentError.classList.remove('hidden');
            paymentMessage.textContent = 'カード決済の準備に失敗しました。';
        }

        if (stripePayButton) {
            stripePayButton.addEventListener('click', async () => {
                stripePayButton.disabled = true;
                setInlineCardError('');
                paymentError.classList.add('hidden');
                paymentMessage.textContent = 'カード会社に接続しています...';

                try {
                    const stripePaymentMethodId = await createStripePaymentMethodId();
                    await chargeStripe(stripePaymentMethodId);
                } catch (error) {
                    paymentError.textContent = error.message;
                    paymentError.classList.remove('hidden');
                    paymentMessage.textContent = 'カード決済に失敗しました。内容を確認してください。';
                    stripePayButton.disabled = false;
                }
            });
        }
    }
} else {
    setTimeout(submitComplete, 1200);
}
</script>

@endsection
