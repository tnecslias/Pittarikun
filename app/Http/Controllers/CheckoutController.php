<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;

class CheckoutController extends Controller
{
    /**
     * 購入情報入力画面
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $cart_items = CartItem::where('user_id', $user->id)
            ->with('storage')
            ->get();

        if ($cart_items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'カートが空です');
        }

        // 支払い方法をセッション保存
        if ($request->has('payment_method')) {
            Session::put('payment_method', $request->payment_method);
        }

        return view('checkout.index', [
            'cart_items'     => $cart_items,
            'name'           => old('name', $user->name),
            'address'        => old('address', $user->address), 
            'phone'          => old('phone', $user->phone),     
            'payment_method' => Session::get('payment_method', ''),
        ]);
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

            // セッション保存
            Session::put('checkout', $data);
        }

        $checkout = Session::get('checkout');

        if (!$checkout) {
            return redirect()->route('checkout.index');
        }

        return view('checkout.confirm', [
            'name'           => $checkout['name'],
            'address'        => $checkout['address'],
            'phone'          => $checkout['phone'],
            'payment_method' => $checkout['payment_method'],
            'cart_items'     => CartItem::where('user_id', Auth::id())
                                ->with('storage')
                                ->get(),
        ]);
    }


    /**
     * 決済処理
     */
    public function payment(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required',
            'address'        => 'required',
            'phone'          => 'required',
            'payment_method' => 'required',
        ]);

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

        $user = Auth::user();

        $user->update([
            'address' => $checkout['address'],
            'phone'   => $checkout['phone'],
        ]);
        $cart_items = CartItem::where('user_id', $user->id)
            ->with('storage')
            ->get();

        if ($cart_items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        // 合計計算
        $total = 0;

        foreach ($cart_items as $item) {
            $total += $item->storage->price * $item->quantity;
        }


        /*
        |--------------------------------------------------------------------------
        | usersテーブル更新（住所・電話番号保存）
        |--------------------------------------------------------------------------
        */
        $user->address = $checkout['address'];
        $user->phone   = $checkout['phone'];
        $user->save();


        /*
        |--------------------------------------------------------------------------
        | orders 保存
        |--------------------------------------------------------------------------
        */
        $order = Order::create([
            'user_id'        => $user->id,
            'name'           => $checkout['name'],
            'address'        => $checkout['address'],
            'phone'          => $checkout['phone'],
            'payment_method' => $checkout['payment_method'],
            'total_price'    => $total,
        ]);


        /*
        |--------------------------------------------------------------------------
        | order_items 保存
        |--------------------------------------------------------------------------
        */
        foreach ($cart_items as $item) {

            OrderItem::create([
                'order_id'   => $order->id,
                'storage_id' => $item->storage_id,
                'price'      => $item->storage->price,
                'quantity'   => $item->quantity,
            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | カート削除
        |--------------------------------------------------------------------------
        */
        CartItem::where('user_id', $user->id)->delete();


        /*
        |--------------------------------------------------------------------------
        | セッション削除
        |--------------------------------------------------------------------------
        */
        Session::forget('checkout');


        return view('checkout.complete');
    }
}
