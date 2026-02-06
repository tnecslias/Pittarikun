@extends('layouts.app')

@section('title', 'ホーム')

@section('content')
<div class="flex flex-col justify-center items-center bg-gray-50">

    {{-- タイトル --}}
    <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">
        収納スペースの大きさを入力してください
    </h2>

    {{-- ========= 入力フォーム========= --}}
    <div class="w-full max-w-lg bg-white shadow-lg rounded-xl p-8">

        {{-- 検索フォーム --}}
        <form method="GET" action="{{ route('storage.search') }}" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium text-gray-700">幅 (cm)</label>
                <input type="number" name="width" placeholder="例: 50"
                    value="{{ request('width') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">高さ (cm)</label>
                <input type="number" name="height" placeholder="例: 30"
                    value="{{ request('height') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">奥行き (cm)</label>
                <input type="number" name="depth" placeholder="例: 36"
                    value="{{ request('depth') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
            </div>

            <button type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-lg shadow">
                検索
            </button>
        </form>

    </div> 

    {{-- 検索結果 --}}

@isset($storages)

    <div class="mt-20 grid gap-6 grid-cols-2 sm:grid-cols-3 md:grid-cols-4">

        @forelse($storages as $s)

            {{--  isFavorited を計算する --}}
            @php
                $isFavorited = false;
                if (auth()->check()) {
                    $favorites = auth()->user()->favorites()->pluck('storage_id');
                    $isFavorited = $favorites->contains($s->id);
                }
            @endphp

<div class="relative group bg-white rounded-xl shadow-md p-4 hover:shadow-xl transition">


    {{-- 画像枠 --}}
    <div class="w-full aspect-square relative rounded-xl overflow-hidden shadow-sm">
    <div x-data="{ open: false }" class="absolute bottom-2 left-2 z-50">

    {{-- ボタン --}}
    <button
        type="button"
        @click.stop="open = !open"
        class="bg-white border border-gray-200 rounded-full px-3 py-1 text-xs font-semibold shadow hover:scale-105 transition z-50 relative"
    >
        サイズ
    </button>

    {{-- ポップアップ --}}
    <div
        x-show="open"
        x-transition
        @click.outside="open = false"
        x-cloak
        class="absolute left-0 bottom-10 bg-white border rounded-lg shadow-xl p-3 text-xs text-gray-700 whitespace-nowrap z-[999]"
        style="display:none;"
    >
        <p>幅：{{ $s->width }}cm</p>
        <p>高さ：{{ $s->height }}cm</p>
        <p>奥行：{{ $s->depth }}cm</p>
    </div>

</div>



        {{-- 画像 --}}
        <img src="{{ asset('storage/'.$s->image) }}"
             alt="{{ $s->name }}"
             class="object-cover w-full h-full">

        {{-- 上部グラデーション --}}
        <div class="absolute inset-0 bg-gradient-to-b from-black/10 to-transparent"></div>

{{-- お気に入り（ハート） --}}
<div class="absolute bottom-2 right-2">

    @auth
        {{-- ===== ログイン時 ===== --}}
        <form method="POST" action="{{ route('favorite.toggle', $s->id) }}">
            @csrf
            <button type="submit"
                class="flex items-center justify-center w-10 h-10 bg-white/80 backdrop-blur-md border border-gray-200 rounded-full shadow hover:scale-110 transition">
                @if($isFavorited)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#e63946" viewBox="0 0 24 24" class="w-6 h-6">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5
                        2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09
                        C13.09 3.81 14.76 3 16.5 3 19.58 3
                        22 5.42 22 8.5c0 3.78-3.4
                        6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#ccc" viewBox="0 0 24 24" class="w-6 h-6">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5
                        2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09
                        C13.09 3.81 14.76 3 16.5 3 19.58 3
                        22 5.42 22 8.5c0 3.78-3.4
                        6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                @endif
            </button>
        </form>
    @endauth

    @guest
        {{-- ===== 未ログイン時（ぼかし） ===== --}}
        <div class="relative">

            <div class="blur-sm opacity-60 pointer-events-none">
                <button
                    class="flex items-center justify-center w-10 h-10 bg-white/80 backdrop-blur-md border border-gray-200 rounded-full shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#ccc" viewBox="0 0 24 24" class="w-6 h-6">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5
                        2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09
                        C13.09 3.81 14.76 3 16.5 3 19.58 3
                        22 5.42 22 8.5c0 3.78-3.4
                        6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </button>
            </div>

        </div>
    @endguest

</div>


                    

    </div>

    {{-- テキスト類 --}}
    <h3 class="mt-6 text-sm font-bold text-gray-800 text-center">
        {{ $s->name }}
    </h3>

    <p class="text-gray-600 mt-1 text-sm text-center">
        ¥{{ number_format($s->price) }}
    </p>
<div class="mt-2 flex flex-wrap gap-2 justify-center">

@if(request('width') && isset($s->fit_count) && $s->fit_count >= 1)

<span class="width-label px-3 py-1 text-xs font-bold rounded-full
    @if($s->remaining_width == 0)
        bg-green-100 text-green-700
    @elseif($s->remaining_width > 0)
        bg-gray-100 text-gray-700
    @else
        bg-red-100 text-red-700
    @endif
"
data-storage-width="{{ request('width') }}"
data-case-width="{{ $s->width }}">

    @if($s->remaining_width == 0)
        幅：ぴったり！
    @elseif($s->remaining_width > 0)
        幅：残り{{ $s->remaining_width }}cm
    @else
        幅：オーバー{{ abs($s->remaining_width) }}cm
    @endif

</span>

@endif



{{-- 高さ --}}
@if(request('height') && isset($s->remaining_height))

    @if($s->remaining_height == 0)
        <span class="px-3 py-1 text-xs font-bold bg-green-100 text-green-700 rounded-full">
            高さ：ぴったり！
        </span>
    @else
        <span class="px-3 py-1 text-xs font-bold bg-gray-100 text-gray-700 rounded-full">
            高さ：残り{{ $s->remaining_height }}cm
        </span>
    @endif

@endif

</div>






    {{-- ボタン・数量 --}}
<div class="mt-2 w-full space-y-3 relative">

@auth
<form method="POST" action="{{ route('cart.add', $s->id) }}" class="space-y-2">
    @csrf

<select name="quantity"
    class="quantity-select border rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-400 w-full"
    data-case-width="{{ $s->width }}"
    data-storage-width="{{ request('width') }}">

    @for ($i = 1; $i <= 10; $i++)
        <option value="{{ $i }}"
            {{ isset($s->fit_count) && $i == $s->fit_count ? 'selected' : '' }}>
            {{ $i }}
        </option>
    @endfor

</select>


    <button type="submit"
        class="w-full bg-blue-500 hover:bg-blue-600 text-white text-sm py-2 rounded-lg shadow">
        カートに追加
    </button>
</form>
@endauth



@guest
<div class="relative w-full">

    {{-- ===== 未ログイン時（ぼかし表示） ===== --}}
    <div class="space-y-3 blur-sm opacity-60 pointer-events-none">

        <select
            class="border rounded-lg px-3 py-1.5 text-sm w-full">
            <option>1</option>
        </select>

        <button
            class="w-full bg-blue-400 text-white text-sm py-2 rounded-lg shadow">
            カートに追加
        </button>

    </div>

{{-- ===== ぼかしの上に重ねるメッセージ ===== --}}
<div class="absolute inset-0 flex items-center justify-center">
    <p class="text-xs text-gray-600 text-center">
        <a href="{{ route('login') }}"
           class="text-blue-600 underline cursor-pointer hover:text-blue-800">
            ログイン
        </a>
        するとお気に入り・カート追加ができます
    </p>
</div>


</div>
@endguest


</div>


</div>

        @empty
            <p class="col-span-full text-center text-gray-500">
                条件に合う収納ケースはありません。
            </p>
        @endforelse

        
    </div>
@endisset






</div>
<script>
document.addEventListener("DOMContentLoaded", function(){

    document.querySelectorAll(".quantity-select").forEach(select => {

        function updateWidth() {

            const caseWidth = parseFloat(select.dataset.caseWidth);
            const storageWidth = parseFloat(select.dataset.storageWidth);
            const qty = parseInt(select.value);

            const remaining = storageWidth - (caseWidth * qty);

            const label = select.closest(".space-y-3")
                                .parentElement
                                .querySelector(".width-label");

            if (!label) return;

            if (remaining === 0) {

                label.textContent = "幅：ぴったり！";
                label.className =
                    "width-label px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700";

            } else if (remaining > 0) {

                label.textContent = "幅：残り" + remaining + "cm";
                label.className =
                    "width-label px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-700";

            } else {

                label.textContent = "幅：オーバー" + Math.abs(remaining) + "cm";
                label.className =
                    "width-label px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700";
            }
        }

        select.addEventListener("change", updateWidth);

        // 初期表示更新
        updateWidth();

    });

});

function toggleSize(id)
{
    const popup = document.getElementById("size-" + id);

    if (popup.classList.contains("hidden"))
    {
        popup.classList.remove("hidden");
    }
    else
    {
        popup.classList.add("hidden");
    }
}

</script>

@endsection
