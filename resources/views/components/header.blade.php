<nav x-data="{ open: false }" class="fixed top-0 left-0 w-full h-16 bg-white shadow-md z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 h-full flex justify-between items-center">

        {{-- ロゴ --}}
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/logo.png') }}" class="h-9 w-9">
            <a href="{{ route('home') }}"
               class="text-xl font-bold text-gray-800 transition">
                ぴったりくん
            </a>
        </div>

        {{-- PCメニュー --}}
        <div class="hidden md:flex items-center gap-6 text-sm font-medium">
            <a href="{{ route('home') }}"
            class="flex items-center gap-1 transition
            {{ request()->routeIs('home')
                ? 'text-blue-500'
                : 'text-gray-700 hover:text-blue-500' }}">

                
                <!-- 検索アイコン -->
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>

                検索
            </a>

            @guest

                {{-- ログイン --}}
                <a href="{{ route('login') }}"
                class="flex items-center gap-1 transition
                {{ request()->routeIs('login')
                    ? 'text-blue-500'
                    : 'text-gray-700 hover:text-blue-500' }}">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 12h-9m0 0l3-3m-3 3l3 3"/>
                    </svg>

                    ログイン
                </a>


            @else

                {{-- お気に入り --}}
                <a href="{{ route('favorites') }}"
                class="flex items-center gap-1 transition
                {{ request()->routeIs('favorites')
                    ? 'text-blue-500'
                    : 'text-gray-700 hover:text-blue-500' }}">


                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5 shrink-0"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2">

                        <path d="M11.645 20.91l-.345.18-.345-.18C5.4 18.01 2 14.85 2 10.5
                                2 7.42 4.42 5 7.5 5
                                c1.74 0 3.41.81 4.5 2.09
                                C13.09 5.81 14.76 5 16.5 5
                                19.58 5 22 7.42 22 10.5
                                c0 4.35-3.4 7.51-8.355 10.41z"/>
                    </svg>

                    <span>お気に入り</span>

                </a>

                {{-- カート --}}
                <a href="{{ route('cart.index') }}"
                class="relative flex items-center gap-1 transition
                {{ request()->routeIs('cart.index', 'checkout.*')
                    ? 'text-blue-500'
                    : 'text-gray-700 hover:text-blue-500' }}">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.25 3h1.386a.75.75 0 01.728.564l.803 3.21M7.5 14.25h9.75l3-7.5H6.621m.879 7.5l-.75 3m0 0h11.25m-11.25 0a1.5 1.5 0 11-3 0m14.25 0a1.5 1.5 0 11-3 0"/>
                    </svg>

                    カート

                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-4
                        bg-red-500 text-white text-[10px]
                        rounded-full min-w-[18px] h-[18px]
                        flex items-center justify-center
                        px-1 shadow-sm ring-2 ring-white">

                            {{ $cartCount > 99 ? '99+' : $cartCount }}
                        </span>
                    @endif

                </a>

                {{-- マイページ --}}
                <a href="{{ route('mypage') }}"
                   class="flex items-center gap-1 transition
                    {{ request()->routeIs('mypage')
                        ? 'text-blue-500'
                        : 'text-gray-700 hover:text-blue-500' }}">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-5 h-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 0115 0"/>
                    </svg>
                    マイページ
                </a>

                {{-- ログアウト --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        onclick="return confirm('ログアウトしますか？')"
                        class="flex items-center gap-1 text-gray-700 hover:text-red-500 transition">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 12h-9m0 0l3-3m-3 3l3 3"/>
                        </svg>

                        ログアウト
                    </button>
                </form>


            @endguest

        </div>

        {{-- モバイルメニューボタン --}}
        <button
            type="button"
            @click="open = !open"
            class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg border border-gray-200 text-gray-700"
            :aria-expanded="open.toString()"
            aria-controls="mobile-menu"
            aria-label="メニューを開閉">
            <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- モバイルメニュー --}}
    <div
        id="mobile-menu"
        x-show="open"
        x-transition
        x-cloak
        @click.outside="open = false"
        class="md:hidden border-t border-gray-100 bg-white shadow-md">
        <div class="px-4 py-3 space-y-3 text-sm font-medium">
            <a href="{{ route('home') }}"
               @click="open = false"
               class="block {{ request()->routeIs('home') ? 'text-blue-500' : 'text-gray-700 hover:text-blue-500' }}">
                検索
            </a>

            @guest
                <a href="{{ route('login') }}"
                   @click="open = false"
                   class="block {{ request()->routeIs('login') ? 'text-blue-500' : 'text-gray-700 hover:text-blue-500' }}">
                    ログイン
                </a>
            @else
                <a href="{{ route('favorites') }}"
                   @click="open = false"
                   class="block {{ request()->routeIs('favorites') ? 'text-blue-500' : 'text-gray-700 hover:text-blue-500' }}">
                    お気に入り
                </a>

                <a href="{{ route('cart.index') }}"
                   @click="open = false"
                   class="block {{ request()->routeIs('cart.index', 'checkout.*') ? 'text-blue-500' : 'text-gray-700 hover:text-blue-500' }}">
                    カート
                    @if($cartCount > 0)
                        <span class="ml-2 inline-flex items-center justify-center bg-red-500 text-white text-[10px] rounded-full min-w-[18px] h-[18px] px-1">
                            {{ $cartCount > 99 ? '99+' : $cartCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('mypage') }}"
                   @click="open = false"
                   class="block {{ request()->routeIs('mypage') ? 'text-blue-500' : 'text-gray-700 hover:text-blue-500' }}">
                    マイページ
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('ログアウトしますか？')"
                            class="block w-full text-left text-gray-700 hover:text-red-500 transition">
                        ログアウト
                    </button>
                </form>
            @endguest
        </div>
    </div>
</nav>
