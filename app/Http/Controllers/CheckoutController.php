<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    /**
     * 購入情報入力画面
     */
public function index(Request $request)
{
    $cart_items = CartItem::where('user_id', Auth::id())
        ->with('storage')
        ->get();

    if ($cart_items->isEmpty()) {
        return redirect()->route('cart.index')
            ->with('error', 'カートが空です');
    }

    $name = Auth::user()->name;

    // 支払い方法をセッションに保存（もし送信されていれば）
    if ($request->has('payment_method')) {
        Session::put('payment_method', $request->input('payment_method'));
    }

    // セッションから支払い方法を取得してビューに渡す
    $payment_method = Session::get('payment_method', '');

    return view('checkout.index', compact('cart_items', 'name', 'payment_method'));
}


    /**
     * 入力内容確認画面
     */
public function confirm(Request $request)
{
    if ($request->isMethod('post')) {
        $data = $request->validate([
            'name'           => 'required',
            'address'        => 'required',
            'phone'          => 'required',
            'payment_method' => 'required',
        ]);

        Session::put('checkout', $data);
    }

    $checkout = Session::get('checkout', []);

    return view('checkout.confirm', [
        'name'           => $checkout['name'] ?? '',
        'address'        => $checkout['address'] ?? '',
        'phone'          => $checkout['phone'] ?? '',
        'payment_method' => $checkout['payment_method'] ?? '',
        'cart_items'     => CartItem::where('user_id', Auth::id())->with('storage')->get(),
    ]);
}





/**
 * 決済処理（ダミー）
 */
public function payment(Request $request)
{
    $data = $request->validate([
        'name'           => 'required',
        'address'        => 'required',
        'phone'          => 'required',
        'payment_method' => 'required',
    ]);

    // セッションに保存
    Session::put('checkout', $data);

    return view('checkout.payment', [
        'payment_method' => $data['payment_method']
    ]);
}

    /**
     * 注文完了
     */
public function complete()
{
    $checkout = Session::get('checkout');

    if (!$checkout) {
        return redirect()->route('cart.index');
    }

    $cart_items = CartItem::where('user_id', Auth::id())
        ->with('storage')
        ->get();

    // 合計計算
    $total = 0;

    foreach ($cart_items as $item) {
        $total += $item->storage->price * $item->quantity;
    }

    // orders 保存
    $order = Order::create([
        'user_id'        => Auth::id(),
        'name'           => $checkout['name'],
        'address'        => $checkout['address'],
        'phone'          => $checkout['phone'],
        'payment_method' => $checkout['payment_method'],
        'total_price'    => $total,
    ]);

    // order_items 保存
    foreach ($cart_items as $item) {
        OrderItem::create([
            'order_id'   => $order->id,
            'storage_id' => $item->storage_id,
            'price'      => $item->storage->price,
            'quantity'   => $item->quantity,
        ]);
    }

    // カート削除
    CartItem::where('user_id', Auth::id())->delete();

    Session::forget('checkout');

    return view('checkout.complete');
    }
}
