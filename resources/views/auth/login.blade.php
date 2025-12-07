@extends('layouts.app')

@section('title', 'ログイン')

@section('content')
    <div class="container mx-auto flex justify-center items-center min-h-[60vh]">

        <div class="w-full max-w-lg bg-white shadow-lg rounded-xl p-8">
            <h2 class="text-xl font-semibold mb-4 text-center">
                @if(request()->routeIs('login')) ログイン @else 新規登録 @endif
            </h2>
            
            {{-- バリデーションエラーメッセージ --}}
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-2 rounded mb-3">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="@if(request()->routeIs('login')) {{ route('login') }} @else {{ route('register') }} @endif" class="space-y-4">
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

                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 transition">
                    @if(request()->routeIs('login')) ログイン @else 登録 @endif
                </button>
            </form>



            <div class="text-center mt-4">
                @if(request()->routeIs('login'))
                    <a href="{{ route('register') }}" class="text-blue-500 hover:underline">新規登録はこちら</a>
                @else
                    <a href="{{ route('login') }}" class="text-blue-500 hover:underline">ログインはこちら</a>
                @endif
            </div>
        </div>

    </div>
@endsection
