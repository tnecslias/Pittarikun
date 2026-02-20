@extends('layouts.app')

@section('title', 'ログイン')

@section('content')
<div class="w-full max-w-5xl mx-auto flex justify-center items-center min-h-[60vh]">

    <div class="w-full max-w-lg bg-white shadow-lg rounded-xl p-5 sm:p-8">

        <h2 class="text-xl font-semibold mb-4 text-center">
            @if(request()->routeIs('login')) ログイン @else 新規登録 @endif
        </h2>

        {{-- バリデーションエラー --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-2 rounded mb-3">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- ▼ 試しアカウント表示（ログイン時のみ） --}}
@if(request()->routeIs('login'))
<div class="fixed left-4 right-4 bottom-4 md:left-auto md:right-6 md:bottom-auto md:top-20 bg-white border border-gray-300 shadow-lg rounded-xl p-4 text-sm z-40 md:w-64">

    <div class="font-bold mb-1 text-gray-800 text-base">
        お試しアカウント
    </div>

    <div class="mb-1">
        <span class="font-semibold">メール:</span><br>
        user1@test.com
    </div>

    <div class="mb-2">
        <span class="font-semibold">パスワード:</span><br>
        testtest
    </div>

    <button type="button"
        onclick="fillTestAccount()"
        class="mt-1 w-full bg-gray-800 text-white py-1.5 rounded-lg hover:bg-gray-900 transition text-sm">
        自動入力する
    </button>

</div>
@endif




        <form method="POST"
            action="@if(request()->routeIs('login')) {{ route('login') }} @else {{ route('register') }} @endif"
            class="space-y-4">

            @csrf

            @if(request()->routeIs('register'))
            <div>
                <label class="block mb-1">名前</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
            </div>
            @endif

            <div>
                <label class="block mb-1">メールアドレス</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block mb-1">パスワード</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>

            <button type="submit"
                class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 transition">
                @if(request()->routeIs('login')) ログイン @else 登録 @endif
            </button>

        </form>


        <div class="text-center mt-4">
            @if(request()->routeIs('login'))
                <a href="{{ route('register') }}" class="text-blue-500 hover:underline">
                    新規登録はこちら
                </a>
            @else
                <a href="{{ route('login') }}" class="text-blue-500 hover:underline">
                    ログインはこちら
                </a>
            @endif
        </div>

    </div>

</div>


{{-- ▼ 自動入力スクリプト --}}
<script>
function fillTestAccount() {
    document.querySelector('input[name="email"]').value = "user1@test.com";
    document.querySelector('input[name="password"]').value = "testtest";
}
</script>

@endsection
