<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Stripe\StripeClient;

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
                'card_number'    => 'nullable|string',
                'card_expiry'    => 'nullable|string',
                'card_cvc'       => 'nullable|string',
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
            'card_number'    => $checkout['card_number'] ?? '',
            'card_expiry'    => $checkout['card_expiry'] ?? '',
            'card_cvc'       => $checkout['card_cvc'] ?? '',
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
            'card_number'    => 'nullable|string',
            'card_expiry'    => 'nullable|string',
            'card_cvc'       => 'nullable|string',
        ]);

        Session::put('checkout', $data);
        Session::forget(['stripe_paid', 'stripe_payment_intent_id']);

        if ($data['payment_method'] === 'credit_card' && !$this->shouldSkipStripePayment()) {
            // 同一注文の重複課金を防ぐため、画面遷移ごとに1つのキーを払い出す
            Session::put('stripe_idempotency_key', (string) Str::uuid());
        } else {
            Session::forget('stripe_idempotency_key');
        }

        return view('checkout.payment', [
            'payment_method'              => $data['payment_method'],
            'card_number'                 => $data['card_number'] ?? '',
            'card_expiry'                 => $data['card_expiry'] ?? '',
            'card_cvc'                    => $data['card_cvc'] ?? '',
            'stripe_publishable_key'      => (string) config('services.stripe.publishable_key', ''),
            'can_bypass_card_validation'  => $this->canBypassCardValidation(),
            'skip_stripe_payment'         => $this->shouldSkipStripePayment(),
            'stripe_billing_name'         => $data['name'] ?? '',
        ]);
    }


    /**
     * Stripeカード決済
     */
    public function stripeCharge(Request $request)
    {
        $request->validate([
            'payment_method'            => 'required|string',
            'stripe_payment_method_id'  => 'nullable|string',
        ]);

        if ($request->payment_method !== 'credit_card') {
            return response()->json(['success' => true]);
        }

        if ($this->shouldSkipStripePayment()) {
            Session::put('stripe_paid', true);
            Session::put('stripe_payment_intent_id', 'skipped-production');

            return response()->json([
                'success' => true,
                'skipped' => true,
            ]);
        }

        if (Session::get('stripe_paid') && Session::get('stripe_payment_intent_id')) {
            return response()->json([
                'success' => true,
                'payment_intent_id' => Session::get('stripe_payment_intent_id'),
                'already_paid' => true,
            ]);
        }

        $checkout = Session::get('checkout');
        if (!$checkout) {
            return response()->json([
                'success' => false,
                'message' => 'セッションが切れています。最初からやり直してください。',
            ], 422);
        }

        $bypassed = false;
        $intentPayload = [
            'amount' => $this->calculateCartTotal(),
            'currency' => 'jpy',
            'confirm' => true,
            'automatic_payment_methods' => [
                'enabled' => true,
                'allow_redirects' => 'never',
            ],
            'description' => 'Order for user #' . Auth::id(),
        ];

        if ($this->canBypassCardValidation()) {
            $bypassed = true;
            // 開発モードでは入力値を無視し、Stripe提供のテストPaymentMethodを使用
            $intentPayload['payment_method'] = 'pm_card_visa';
        } else {
            $stripePaymentMethodId = trim((string) $request->input('stripe_payment_method_id', ''));
            if ($stripePaymentMethodId === '') {
                return response()->json([
                    'success' => false,
                    'message' => 'カード情報の送信に失敗しました。もう一度お試しください。',
                ], 422);
            }

            $intentPayload['payment_method'] = $stripePaymentMethodId;
        }

        $secret = (string) config('services.stripe.secret');
        if ($secret === '') {
            return response()->json([
                'success' => false,
                'message' => 'STRIPE_SECRET が未設定です。',
            ], 500);
        }

        try {
            $stripe = new StripeClient($secret);

            $intent = $stripe->paymentIntents->create($intentPayload, [
                'idempotency_key' => Session::get('stripe_idempotency_key') ?? (string) Str::uuid(),
            ]);

            if ($intent->status !== 'succeeded') {
                return response()->json([
                    'success' => false,
                    'message' => '決済が完了しませんでした。ステータス: ' . $intent->status,
                ], 422);
            }

            Session::put('stripe_paid', true);
            Session::put('stripe_payment_intent_id', $intent->id);

            return response()->json([
                'success' => true,
                'payment_intent_id' => $intent->id,
                'bypassed' => $bypassed,
            ]);
        } catch (\Throwable $e) {
            $message = $e->getMessage();
            if ($this->isStripeTestMode() && !$this->canBypassCardValidation()) {
                $message .= ' テスト環境では Stripe テストカード（例: 4242 4242 4242 4242）を使用してください。';
            }

            return response()->json([
                'success' => false,
                'message' => $message,
            ], 422);
        }
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

        if (($checkout['payment_method'] ?? '') === 'credit_card' && !Session::get('stripe_paid')) {
            return redirect()->route('checkout.index')->with('error', 'カード決済が完了していません。');
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
        $total = $this->calculateCartTotal();


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
        Session::forget('stripe_paid');
        Session::forget('stripe_payment_intent_id');
        Session::forget('stripe_idempotency_key');


        return view('checkout.complete');
    }

    private function calculateCartTotal(): int
    {
        $total = 0;
        $items = CartItem::where('user_id', Auth::id())->with('storage')->get();

        foreach ($items as $item) {
            if (!$item->storage) {
                continue;
            }
            $total += $item->storage->price * $item->quantity;
        }

        return $total;
    }

    private function canBypassCardValidation(): bool
    {
        return (bool) config('services.stripe.allow_any_card', false)
            && app()->environment(['local', 'testing']);
    }

    private function shouldSkipStripePayment(): bool
    {
        return app()->environment('production');
    }

    private function isStripeTestMode(): bool
    {
        $secret = (string) config('services.stripe.secret', '');

        return str_starts_with($secret, 'sk_test_');
    }

}
