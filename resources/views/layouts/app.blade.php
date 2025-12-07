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

    {{-- ★★ メインは flex-grow で余白を埋める ★★ --}}
    <main class="pt-20 flex-grow">
        @yield('content')
    </main>

    {{-- フッター（mt-auto は footer.blade 側にあるのでOK） --}}
    @include('components.footer')

</body>
</html>
