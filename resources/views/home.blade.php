@extends('layouts.app')

@section('title', 'ホーム')

@section('content')
<div class="flex flex-col justify-center items-center bg-gray-50">


    <div class="w-full max-w-2xl mb-6">
        <div class="flex items-stretch gap-3 sm:gap-4">
            <div class="shrink-0 self-stretch -ml-2 sm:-ml-3">
                <img src="{{ asset('images/pittari-guide.before-bg-clear.png') }}"
                     alt="ぴったりくん"
                     class="h-full w-auto max-w-[220px] object-contain">
            </div>

            <div class="relative flex-1 ml-6 sm:ml-10 rounded-2xl border-2 border-gray-400 bg-white p-4 shadow-md">
                <p class="text-sm font-semibold text-gray-800 mb-3 text-center">測ったサイズを入力して、ぴったり収納を見つけよう！</p>
                <svg viewBox="0 0 760 380" class="w-full h-auto" role="img" aria-label="棚の幅、高さ、奥行きの測り方">
                <defs>
                    <marker id="arrow-end" markerWidth="10" markerHeight="10" refX="9" refY="5" orient="auto" markerUnits="strokeWidth">
                        <path d="M0,0 L10,5 L0,10 Z" fill="#2563EB"></path>
                    </marker>
                    <marker id="arrow-start" markerWidth="10" markerHeight="10" refX="1" refY="5" orient="auto" markerUnits="strokeWidth">
                        <path d="M10,0 L0,5 L10,10 Z" fill="#2563EB"></path>
                    </marker>
                </defs>

                {{-- 棚板（上・中・下） --}}
                <polygon points="120,70 540,70 620,95 200,95" fill="#E8CDA5" stroke="#B08968" stroke-width="2"></polygon>
                <polygon points="120,180 540,180 620,205 200,205" fill="#E8CDA5" stroke="#B08968" stroke-width="2"></polygon>
                <polygon points="120,290 540,290 620,315 200,315" fill="#E8CDA5" stroke="#B08968" stroke-width="2"></polygon>

                {{-- フレーム --}}
                <line x1="120" y1="70" x2="120" y2="300" stroke="#94A3B8" stroke-width="4"></line>
                <line x1="200" y1="95" x2="200" y2="325" stroke="#94A3B8" stroke-width="4"></line>
                <line x1="540" y1="70" x2="540" y2="300" stroke="#94A3B8" stroke-width="4"></line>
                <line x1="620" y1="95" x2="620" y2="325" stroke="#94A3B8" stroke-width="4"></line>

                {{-- 斜め補強 --}}
                <line x1="200" y1="95" x2="540" y2="300" stroke="#CBD5E1" stroke-width="3"></line>
                <line x1="540" y1="95" x2="200" y2="300" stroke="#CBD5E1" stroke-width="3"></line>

                {{-- 幅（手前の線） --}}
                <line x1="200" y1="232" x2="620" y2="232" stroke="#2563EB" stroke-width="3"
                    marker-start="url(#arrow-start)" marker-end="url(#arrow-end)"></line>
                <text x="410" y="224" text-anchor="middle" fill="#1E40AF" font-size="18" font-weight="700">幅</text>

                {{-- 高さ（1段分） --}}
                <line x1="168" y1="95" x2="168" y2="195" stroke="#2563EB" stroke-width="3"
                    marker-start="url(#arrow-start)" marker-end="url(#arrow-end)"></line>
                <text x="142" y="149" text-anchor="middle" fill="#1E40AF" font-size="18" font-weight="700">高さ</text>

                {{-- 奥行き --}}
                <line x1="555" y1="56" x2="635" y2="82" stroke="#2563EB" stroke-width="3"
                    marker-start="url(#arrow-start)" marker-end="url(#arrow-end)"></line>
                <text x="688" y="72" text-anchor="middle" fill="#1E40AF" font-size="18" font-weight="700">奥行き</text>
                </svg>
                <div class="absolute left-0 top-1/2 -translate-y-1/2 h-7 w-[3px] bg-white"></div>
                <svg class="absolute left-0 top-1/2 -translate-x-[14px] -translate-y-1/2" width="14" height="24" viewBox="0 0 14 24" aria-hidden="true">
                    <polygon points="14,1 1,12 14,23" fill="#FFFFFF"></polygon>
                    <line x1="14" y1="1" x2="1" y2="12" stroke="#9CA3AF" stroke-width="2"></line>
                    <line x1="1" y1="12" x2="14" y2="23" stroke="#9CA3AF" stroke-width="2"></line>
                </svg>
            </div>
        </div>
    </div>

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

<span @class([
    'width-label px-3 py-1 text-xs font-bold rounded-full',
    'bg-green-100 text-green-700' => $s->remaining_width == 0,
    'bg-gray-100 text-gray-700' => $s->remaining_width > 0,
    'bg-red-100 text-red-700' => $s->remaining_width < 0,
])
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
