@extends('layouts.app')

@section('title', '決済処理中')

@section('content')

<div class="max-w-lg mx-auto bg-white p-6 rounded-xl shadow text-center">

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
    <p class="text-gray-600">
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
setTimeout(() => {
    document.getElementById('completeForm').submit();
}, 2000);
</script>

@endsection
