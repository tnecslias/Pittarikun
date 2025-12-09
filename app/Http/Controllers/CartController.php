<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
use App\Models\Storage;

class CartController extends Controller
{
    /**
     * カート一覧ページ
     */
    public function index()
    {
        $user = Auth::user();

        // ユーザーのカート一覧を取得
        $cart_items = CartItem::where('user_id', $user->id)
            ->with('storage') // ← 商品情報(storage)を一緒に取得
            ->get();

        return view('cart.index', compact('cart_items'));
    }

    /**
     * カートに追加
     */
    public function add($id)
    {
        $user = Auth::user();

        // すでに同じ商品があるか確認
        $item = CartItem::where('user_id', $user->id)
            ->where('storage_id', $id)
            ->first();

        if ($item) {
            // 数量 +1
            $item->quantity += 1;
            $item->save();
        } else {
            // 新規追加
            CartItem::create([
                'user_id'     => $user->id,
                'storage_id'  => $id,
                'quantity'    => 1,
            ]);
        }

        return back()->with('success', 'カートに追加しました！');
    }

    /**
     * カートから削除
     */
    public function remove($id)
    {
        $user = Auth::user();

        CartItem::where('user_id', $user->id)
            ->where('storage_id', $id)
            ->delete();

        return back()->with('success', 'カートから削除しました');
    }
}
