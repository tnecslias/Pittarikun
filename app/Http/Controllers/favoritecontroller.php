<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;
use App\Models\Storage;  // ← 商品モデル（storage）を使う場合は必要

class FavoriteController extends Controller
{
    /**
     * お気に入り一覧ページ
     */
    public function index()
    {
        $user = Auth::user();

        // ユーザーのお気に入り一覧を取得（storage も取得）
        $favorites = Favorite::where('user_id', $user->id)
            ->with('storage')
            ->get();

        return view('favorites', compact('favorites'));
    }

    /**
     * お気に入りの追加・削除 (toggle)
     */
    public function toggle($id)
    {
        $user = Auth::user();

        // 既に登録されているか確認
        $exists = Favorite::where('user_id', $user->id)
            ->where('storage_id', $id)
            ->exists();

        if ($exists) {
            // 💔 登録済 → 削除
            Favorite::where('user_id', $user->id)
                ->where('storage_id', $id)
                ->delete();
        } else {
            // ❤️ 未登録 → 追加
            Favorite::create([
                'user_id'    => $user->id,
                'storage_id' => $id,
            ]);
        }

        return back()->with('success', 'お気に入りを更新しました');
    }
}
