@extends('layouts.app')

@section('title', 'マイページ')

@section('content')
<div class="flex flex-col items-center">

    {{-- ここをカードの上に大きく表示 --}}
    <h2 class="text-2xl font-bold mb-6">
        ようこそ、{{ $user->name }} さん！
    </h2>

    <div class="w-full max-w-lg bg-white shadow-lg rounded-xl p-8">
        <p><strong>名前：</strong> {{ $user->name }}</p>
        <p><strong>メール：</strong> {{ $user->email }}</p>
        <p><strong>登録日：</strong> {{ $user->created_at }}</p>

        <div class="mt-4 text-center">
            <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:underline">
                プロフィールを編集する
            </a>
        </div>
    </div>
</div>
@endsection
