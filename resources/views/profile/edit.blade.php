@extends('layouts.app')

@section('title', 'プロフィール編集')

@section('content')
<div class="flex justify-center">
    <div class="w-full max-w-lg bg-white shadow-lg rounded-xl p-8">

        <h2 class="text-2xl font-bold mb-6 text-center">
            プロフィール編集
        </h2>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            {{-- 名前 --}}
            <div class="mb-4">
                <label for="name" class="block font-bold mb-1">
                    名前
                </label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    class="w-full border rounded px-3 py-2
                           @error('name') border-red-500 @enderror"
                >

                @error('name')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- メールアドレス --}}
            <div class="mb-6">
                <label for="email" class="block font-bold mb-1">
                    メールアドレス
                </label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    class="w-full border rounded px-3 py-2
                           @error('email') border-red-500 @enderror"
                >

                @error('email')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- ボタン --}}
            <div class="flex justify-between items-center">
                <a href="{{ route('mypage') }}"
                   class="text-gray-600 hover:underline">
                    ← 戻る
                </a>

                <button
                    type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded
                           hover:bg-blue-700 transition"
                >
                    更新する
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
