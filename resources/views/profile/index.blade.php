@extends('layouts.app')

@section('title', 'マイページ')

@section('content')
<div class="container mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-4">マイページ</h1>

    <div class="bg-white p-6 shadow rounded">
        <h2 class="text-lg">ようこそ、{{ $user->name }} さん！</h2>

        <p><strong>名前：</strong> {{ $user->name }}</p>
        <p><strong>メール：</strong> {{ $user->email }}</p>
        <p><strong>登録日：</strong> {{ $user->created_at }}</p>

        <div class="mt-4">
            <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:underline">
                プロフィールを編集する
            </a>
        </div>
    </div>
</div>
@endsection
