<?php

require __DIR__.'/auth.php';

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;


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

    // ▼ カート追加
    Route::post('/cart/add/{id}', [CartController::class, 'add'])
        ->name('cart.add');
});
