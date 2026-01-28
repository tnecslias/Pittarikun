<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\CartItem;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        Log::info('---- LoginController login() START ----');
        Log::info('cart param = ' . $request->cart);

        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();
            Log::info('Login success. UserID = ' . Auth::id());

            // ★ カート追加処理
            if ($request->has('cart')) {
                Log::info('cart param detected: '.$request->cart);

                $storageId = (int)$request->cart;

                CartItem::updateOrCreate(
                    [
                        'user_id' => Auth::id(),
                        'storage_id' => $storageId,
                    ],
                    [
                        'quantity' => 1,
                    ]
                );

                Log::info("CartItem added: user_id=".Auth::id()." storage_id=".$storageId);

                return redirect()->route('cart.index');
            }

            Log::info('cart param NOT detected. Redirecting to home');

            return redirect()->intended('/');
        }

        Log::info('Login failed.');

        return back()->withErrors([
            'email' => 'メールアドレスまたはパスワードが間違っています。',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
