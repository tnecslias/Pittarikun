<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MypageController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        return view('mypage', compact('user'));
    }
}
