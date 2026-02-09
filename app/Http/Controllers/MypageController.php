<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Order;

class MypageController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        // 注文履歴取得（新しい順）
        $orders = Order::where('user_id', $user->id)
            ->with('items.storage')
            ->latest()
            ->get();

        return view('mypage', compact('user', 'orders'));
    }
}
