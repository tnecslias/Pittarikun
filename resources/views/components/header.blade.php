<nav class="fixed top-0 left-0 w-full h-16 bg-white shadow-md z-50">
    <div class="container mx-auto px-6 h-full flex justify-between items-center">

        {{-- ロゴ --}}
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/logo.png') }}" class="h-9 w-9">
            <a href="{{ route('home') }}"
               class="text-xl font-bold text-gray-800 transition">
                ぴったりくん
            </a>
        </div>

        {{-- メニュー --}}
        <div class="flex items-center gap-6 text-sm font-medium">
            <a href="{{ route('home') }}"
            class="flex items-center gap-1 text-gray-700 hover:text-blue-500 transition">
                
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
                   class="flex items-center gap-1 text-gray-700 hover:text-blue-500 transition">
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
                class="inline-flex items-center gap-1 text-gray-700 hover:text-pink-500 transition leading-none">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 shrink-0"
                        viewBox="0 0 24 24"
                        fill="currentColor">

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
                   class="relative flex items-center gap-1 text-gray-700 hover:text-blue-500 transition mr-3">

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
                   class="flex items-center gap-1 text-gray-700 hover:text-blue-500 transition">
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
    </div>
</nav>
