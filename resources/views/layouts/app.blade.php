<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - ぴったりくん</title>

    {{-- Tailwind / Vite --}}
    @vite('resources/css/app.css')
</head>

{{-- ★★ フッター固定のために flex-col + min-h-screen を追加！ ★★ --}}
<body class="bg-gray-50 min-h-screen flex flex-col">

    {{-- ヘッダー --}}
    @include('components.header')

    <main class="pt-20 flex-grow">

    {{-- ▼ メッセージ表示（success / error） --}}
{{-- ▼ 中央モーダル ▼ --}}
@if(session('success'))
<div id="center-modal-wrapper"
     class="fixed inset-0 bg-black/40 flex justify-center items-center z-50
            opacity-0 transition-opacity duration-500">

    <div id="center-modal"
         class="bg-white w-80 px-6 py-5 rounded-xl shadow-xl text-center
                opacity-0 scale-95 transition-all duration-500">
        <p class="text-lg font-semibold text-gray-800">
            {{ session('success') }}
        </p>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const wrapper = document.getElementById("center-modal-wrapper");
    const modal = document.getElementById("center-modal");

    // フェードイン
    setTimeout(() => {
        wrapper.style.opacity = 1;
        modal.style.opacity = 1;
        modal.style.transform = "scale(1)";
    }, 100);

    // 3秒後にフェードアウト
    setTimeout(() => {
        wrapper.style.opacity = 0;
        modal.style.opacity = 0;
        modal.style.transform = "scale(0.95)";
    }, 3000);

    // 完全に削除
    setTimeout(() => {
        wrapper.remove();
    }, 3600);
});
</script>
@endif

@if(session('error'))
<div id="center-modal-wrapper"
     class="fixed inset-0 bg-black/40 flex justify-center items-center z-50 opacity-0 transition-opacity duration-500">

    <div id="center-modal"
         class="bg-red-500 w-80 px-6 py-5 rounded-xl shadow-xl text-center
                text-white opacity-0 scale-95 transition-all duration-500">
        <p class="text-lg font-semibold">
            {{ session('error') }}
        </p>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const wrapper = document.getElementById("center-modal-wrapper");
    const modal = document.getElementById("center-modal");

    setTimeout(() => {
        wrapper.style.opacity = 1;
        modal.style.opacity = 1;
        modal.style.transform = "scale(1)";
    }, 100);

    setTimeout(() => {
        wrapper.style.opacity = 0;
        modal.style.opacity = 0;
    }, 3000);

    setTimeout(() => wrapper.remove(), 3600);
});
</script>
@endif


    {{-- ▼ ページごとのコンテンツ --}}
    @yield('content')

</main>


    {{-- フッター（mt-auto は footer.blade 側にあるのでOK） --}}
    @include('components.footer')

</body>


</html>
