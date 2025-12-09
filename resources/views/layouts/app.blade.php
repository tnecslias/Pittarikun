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
     class="fixed inset-0 bg-black/40 flex justify-center items-center z-50 opacity-0 transition-opacity duration-500">

    <div id="center-modal"
         class="bg-white w-80 px-6 py-5 rounded-xl shadow-xl text-center opacity-0 scale-95 transition-all duration-500">
        <p class="text-lg font-semibold text-gray-800">
            {{ session('success') }}
        </p>
    </div>

</div>
@endif

<script>
document.addEventListener("DOMContentLoaded", () => {
    const wrapper = document.getElementById("center-modal-wrapper");
    const modal = document.getElementById("center-modal");

    if (wrapper && modal) {
        // モーダルをフェードイン
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

        // 消えたら削除
        setTimeout(() => {
            wrapper.remove();
        }, 3600);
    }
});
</script>


    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 text-center">
            {{ session('error') }}
        </div>
    @endif

    {{-- ▼ ページごとのコンテンツ --}}
    @yield('content')

</main>


    {{-- フッター（mt-auto は footer.blade 側にあるのでOK） --}}
    @include('components.footer')

</body>


</html>
