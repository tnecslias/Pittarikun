<?php

require __DIR__.'/auth.php';

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;

use Illuminate\Support\Facades\Auth;

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');   // ← ログイン画面へ戻す
})->name('logout');


Route::middleware('auth')->group(function () {

    // カート
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

    // お気に入り
    Route::post('/favorite/toggle/{id}', [FavoriteController::class, 'toggle'])->name('favorite.toggle');
    Route::get('/favorite', [FavoriteController::class, 'index'])->name('favorite.index');

});

// ▼ ホーム（誰でもアクセスOK）
Route::get('/', [HomeController::class, 'index'])->name('home');


// ▼ 収納スペース検索
Route::get('/storage/search', [StorageController::class, 'search'])->name('storage.search');
Route::post('/storage/submit', [StorageController::class, 'submit'])->name('storage.submit');


// ▼ ログイン必須
Route::middleware('auth')->group(function () {

    // プロフィール
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // お気に入り一覧
    Route::get('/favorites', [FavoriteController::class, 'index'])
        ->name('favorites')
        ->middleware('auth');

    // トグル
    Route::post('/favorites/toggle/{id}', [FavoriteController::class, 'toggle'])
        ->name('favorite.toggle')
        ->middleware('auth');


    // ▼ カート一覧
    Route::get('/cart', [CartController::class, 'index'])->name('cart');

    Route::middleware('auth')->group(function () {

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');

    Route::post('/cart/increase/{id}', [CartController::class, 'increase'])->name('cart.increase');
    Route::post('/cart/decrease/{id}', [CartController::class, 'decrease'])->name('cart.decrease');


    // カート削除
    Route::delete('/cart/{id}', [App\Http\Controllers\CartController::class, 'remove'])
        ->name('cart.remove');

});
});
