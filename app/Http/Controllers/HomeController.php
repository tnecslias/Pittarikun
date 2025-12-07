<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // ログインユーザー情報（未ログインなら null）
        return view('home', compact('user'));
    }
}
