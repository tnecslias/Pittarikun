@extends('layouts.app')

@section('title', '注文完了')

@section('content')
<div class="w-full max-w-md mx-auto bg-white p-4 sm:p-6 rounded-xl shadow text-center">

    <h2 class="text-2xl font-bold text-black mb-4">
        ご注文ありがとうございました！
    </h2>

    <p class="text-sm text-gray-600 mb-6">
        ご注文を正常に受け付けました。
    </p>

    <a href="{{ route('home') }}"
       class="inline-block bg-blue-500 hover:bg-blue-600
              text-white px-6 py-2 rounded-lg">
        トップへ戻る
    </a>

</div>
@endsection
