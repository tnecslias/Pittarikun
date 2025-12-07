@extends('layouts.app')

@section('title', 'お気に入り一覧')

@section('content')
<div class="flex justify-center">
    <div class="w-full"> 
        
        <h2 class="text-2xl font-semibold mb-2 text-center">お気に入り一覧</h2>

        @forelse($favorites as $item)
            <div class="bg-white p-4 rounded shadow flex justify-between items-center mb-3">
                <span>{{ $item->name }}</span>
                <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                    削除
                </button>
            </div>
        @empty
            <p class="text-center text-gray-600">お気に入りはありません。</p>
        @endforelse

    </div>
</div>

@endsection
