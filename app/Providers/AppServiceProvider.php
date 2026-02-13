<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {

            // ログインしている場合のみ、そのユーザのカート件数を取得
            $cartCount = Auth::check()
                ? CartItem::where('user_id', Auth::id())->count()
                : 0;

            // 全ビューで使える変数として共有
            $view->with('cartCount', $cartCount);
        });
    }
}
