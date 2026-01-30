<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;

class CheckoutController extends Controller
{
    /**
     * 購入情報入力画面
     */
    public function index()
    {
        $cart_items = CartItem::where('user_id', Auth::id())
            ->with('storage')
            ->get();

        if ($cart_items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'カートが空です');
        }

        $name = Auth::user()->name;

        return view('checkout.index', compact('cart_items', 'name'));
    }

    /**
     * 入力内容確認画面
     */
    public function confirm(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required',
            'address' => 'required',
            'phone'   => 'required',
        ]);

        $cart_items = CartItem::where('user_id', Auth::id())
            ->with('storage')
            ->get();

        return view('checkout.confirm', [
            'name'       => $data['name'],
            'address'    => $data['address'],
            'phone'      => $data['phone'],
            'cart_items' => $cart_items,
        ]);
    }

    /**
     * 注文完了画面
     */
    public function complete(Request $request)
    {
        // ・注文データ保存
        // ・在庫減算
    CartItem::where('user_id', Auth::id())->delete();

        return view('checkout.complete');
    }
}
