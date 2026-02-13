<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - ぴったりくん</title>

    {{-- Tailwind / Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col opacity-0">

    {{-- ヘッダー --}}
    @include('components.header')


    {{-- ▼ トースト通知（success / error） --}}
    @if(session('success') || session('error'))
    <div id="toast"
         class="fixed top-24 left-1/2 -translate-x-1/2 z-[9999]
                opacity-0 -translate-y-5
                transition-all duration-500">

        <div class="
            px-6 py-3 rounded-lg shadow-lg text-white font-semibold
            {{ session('success') ? 'bg-gray-800' : 'bg-red-500' }}
        ">
            {{ session('success') ?? session('error') }}
        </div>

    </div>

    <script>
    document.addEventListener("DOMContentLoaded", () => {

        const toast = document.getElementById("toast");

        // フェードイン
        setTimeout(() => {
            toast.style.opacity = "1";
            toast.style.transform = "translate(-50%, 0)";
        }, 50);

        // フェードアウト
        setTimeout(() => {
            toast.style.opacity = "0";
            toast.style.transform = "translate(-50%, -20px)";
        }, 1800);

        // 削除
        setTimeout(() => {
            toast.remove();
        }, 2300);

    });
    </script>
    @endif


    <main class="pt-24 flex-grow px-4 sm:px-6 lg:px-8 max-w-8xl mx-auto w-full">

        {{-- ▼ ページごとのコンテンツ --}}
        @yield('content')

    </main>


    {{-- フッター --}}
    @include('components.footer')


<script>
document.addEventListener("DOMContentLoaded", function () {

    const savedPosition = sessionStorage.getItem("scrollPosition");

    if (savedPosition !== null) {
        window.scrollTo(0, parseInt(savedPosition));
        sessionStorage.removeItem("scrollPosition");
    }

    // スクロール復元が終わってから表示
    document.body.classList.remove("opacity-0");

    /*
    ============================
    フォーム送信時に保存
    ============================
    */
    document.querySelectorAll("form").forEach(form => {
        form.addEventListener("submit", function () {
            sessionStorage.setItem("scrollPosition", window.scrollY);
        });
    });

    /*
    ============================
    リンククリック時にも保存
    ============================
    */
    document.querySelectorAll("a").forEach(link => {
        if (link.href && !link.href.startsWith("#")) {
            link.addEventListener("click", function () {
                sessionStorage.setItem("scrollPosition", window.scrollY);
            });
        }
    });

});
</script>


</body>
</html>
