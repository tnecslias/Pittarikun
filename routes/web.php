<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MypageController;

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| ログアウト
|--------------------------------------------------------------------------
*/
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| ホーム（誰でもOK）
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| 収納スペース検索
|--------------------------------------------------------------------------
*/
Route::get('/storage/search', [StorageController::class, 'search'])->name('storage.search');
Route::post('/storage/submit', [StorageController::class, 'submit'])->name('storage.submit');

/*
|--------------------------------------------------------------------------
| ログイン必須
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    | マイページ
    */
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');

    /*
    | プロフィール
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    /*
    | お気に入り
    */
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');
    Route::post('/favorites/toggle/{id}', [FavoriteController::class, 'toggle'])->name('favorite.toggle');

    /*
    | カート
    */
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/increase/{id}', [CartController::class, 'increase'])->name('cart.increase');
    Route::post('/cart/decrease/{id}', [CartController::class, 'decrease'])->name('cart.decrease');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');

    /*
    | チェックアウト
    */
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');
    Route::post('/checkout/payment', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::post('/checkout/complete', [CheckoutController::class, 'complete'])->name('checkout.complete');
});
