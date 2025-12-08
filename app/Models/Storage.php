<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Storage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'width',
        'height',
        'depth',
        'price',
        'image',
    ];

    /**
     * この収納に対する「お気に入り」一覧
     */
    public function favorites()
    {
        return $this->hasMany(\App\Models\Favorite::class, 'storage_id');
    }

    /**
     * ログイン中ユーザーがこの収納をお気に入り登録しているか？
     * → $storage->is_favorited で使える
     *
     * 安全策：
     * - Auth ファサードを使う（グローバル helper を避ける）
     * - 非 HTTP コンテキストや例外時は false を返す
     */
    public function getIsFavoritedAttribute()
    {
        try {
            if (!Auth::check()) {
                return false;
            }

            return $this->favorites()
                ->where('user_id', Auth::id())
                ->exists();
        } catch (\Throwable $e) {
            // 何か問題が起きてもアプリを壊さないように false を返す
            return false;
        }
    }
}
