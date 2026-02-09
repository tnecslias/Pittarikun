@extends('layouts.app')

@section('title', 'マイページ')

@section('content')
<div class="flex flex-col items-center">

    {{-- タイトル --}}
    <h2 class="text-2xl font-bold mb-6">
        ようこそ、{{ $user->name }} さん！
    </h2>

    {{-- ユーザー情報 --}}
    <div class="w-full max-w-lg bg-white shadow-lg rounded-xl p-8 mb-6">
        <p><strong>名前：</strong> {{ $user->name }}</p>
        <p><strong>メール：</strong> {{ $user->email }}</p>
        <p><strong>登録日：</strong> {{ $user->created_at->format('Y年m月d日') }}</p>

        <div class="mt-4 text-center">
            <a href="{{ route('profile.edit') }}"
               class="text-blue-600 hover:underline">
                プロフィールを編集する
            </a>
        </div>
    </div>

    {{-- 注文履歴 --}}
    <div class="w-full max-w-lg bg-white shadow-lg rounded-xl p-8">

        <h3 class="text-xl font-bold mb-4">
            注文履歴
        </h3>

        @if($orders->isEmpty())

            <p class="text-gray-500">
                注文履歴はありません
            </p>

        @else

            @foreach($orders as $order)

                <div class="border rounded-lg p-4 mb-4">

                    {{-- 注文ヘッダー --}}
                    <div class="flex justify-between items-center mb-2">

                        <div>
                            <p class="font-bold">
                                注文番号: {{ $order->id }}
                            </p>

                            <p class="text-sm text-gray-500">
                                {{ $order->created_at->format('Y年m月d日 H:i') }}
                            </p>
                        </div>

                        <div class="text-right font-bold text-lg">
                            ¥{{ number_format($order->total_price) }}
                        </div>

                    </div>

                    {{-- 支払い方法 --}}
                    <p class="text-sm mb-2">
                        支払い方法：
                        @switch($order->payment_method)
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
                                不明
                        @endswitch
                    </p>

                    {{-- 商品一覧 --}}
                    <div class="border-t pt-2 text-sm">

                        @foreach($order->items as $item)

                            <div class="flex justify-between">

                                <span>
                                    {{ $item->storage->name }}
                                </span>

                                <span>
                                    ¥{{ number_format($item->price) }}
                                    × {{ $item->quantity }}
                                </span>

                            </div>

                        @endforeach

                    </div>

                </div>

            @endforeach

        @endif

    </div>

</div>
@endsection
