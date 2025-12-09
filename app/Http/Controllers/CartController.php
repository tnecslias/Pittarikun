<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;

class CartController extends Controller
{
    /**
     * カート一覧
     */
    public function index()
    {
        $user = Auth::user();

        $cart_items = CartItem::where('user_id', $user->id)
            ->with('storage')
            ->get();

        return view('cart.index', compact('cart_items'));
    }

    /**
     * カートに追加（数量対応）
     */
    public function add(Request $request, $id)
    {
        $user = Auth::user();

        // 数量を取得（デフォルト1）
        $quantity = (int) $request->input('quantity', 1);

        // カートに同じ商品があるか？
        $item = CartItem::where('user_id', $user->id)
            ->where('storage_id', $id)
            ->first();

        if ($item) {
            // 数量を追加
            $item->quantity += $quantity;
            $item->save();
        } else {
            // 新規追加
            CartItem::create([
                'user_id'    => $user->id,
                'storage_id' => $id,
                'quantity'   => $quantity,
            ]);
        }

        return back()->with('success', 'カートに追加しました');
    }

    /**
     * 数量 +1
     */
    public function increase($storage_id)
    {
        $user = Auth::user();

        $item = CartItem::where('user_id', $user->id)
            ->where('storage_id', $storage_id)
            ->first();

        if ($item) {
            $item->quantity += 1;
            $item->save();
        }

        return back()->with('success', '数量を変更しました');
    }

    /**
     * 数量 -1（0以下で自動削除）
     */
    public function decrease($storage_id)
    {
        $user = Auth::user();

        $item = CartItem::where('user_id', $user->id)
            ->where('storage_id', $storage_id)
            ->first();

        if ($item) {
            if ($item->quantity > 1) {
                $item->quantity -= 1;
                $item->save();
            } else {
                $item->delete();
            }
        }

        return back()->with('success', '数量を変更しました');
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
