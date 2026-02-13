<nav class="fixed top-0 left-0 w-full h-16 bg-white shadow-lg z-50">
    <div class="container mx-auto px-6 h-full flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <img src="{{ asset('images/logo.png') }}" alt="Pittarikun Logo" class="h-8 w-8">
            <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800">ぴったりくん</a>
        </div>
        <div class="flex items-center space-x-4">
            @guest
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-500 font-medium">ホーム</a>
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-500 font-medium">ログイン</a>
            @else
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-500 font-medium">ホーム</a>
                <a href="{{ route('favorites') }}" class="text-gray-700 hover:text-blue-500 font-medium">お気に入り</a>
                <a href="{{ route('cart.index') }}" class="relative inline-block">
                    カート

                    @auth
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-3
                                        bg-red-500 text-white text-xs
                                        rounded-full w-5 h-5 flex
                                        items-center justify-center">
                                {{ $cartCount > 99 ? '99+' : $cartCount }}
                            </span>
                        @endif
                    @endauth
                </a>
                <a href="{{ route('mypage') }}" class="text-gray-700 hover:text-blue-500 font-medium">マイページ</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-700 hover:text-red-500 font-medium">ログアウト</button>
                </form>
            @endguest
        </div>
    </div>
</nav>
