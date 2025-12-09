<nav class="bg-white shadow-lg z-10">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <img src="{{ asset('images/logo.png') }}" alt="Pittarikun Logo" class="h-8 w-8">
            <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800 hover:text-blue-500">ぴったりくん</a>
        </div>
        <div class="flex items-center space-x-4">
            @guest
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-500 font-medium">Home</a>
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-500 font-medium">ログイン</a>
            @else
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-500 font-medium">ホーム</a>
                <a href="{{ route('favorites') }}" class="text-gray-700 hover:text-blue-500 font-medium">お気に入り</a>
                <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-blue-500 font-medium">カート</a>
                <a href="{{ route('profile.edit') }}" class="text-gray-700 hover:text-blue-500 font-medium">マイページ</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-700 hover:text-red-500 font-medium">ログアウト</button>
                </form>
            @endguest
        </div>
    </div>
</nav>
