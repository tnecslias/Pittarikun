<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // バリデーション（uniqueは使わない）
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // ★ 名前 AND メールの複合チェック ★
        $validator->after(function ($validator) use ($request) {
            $exists = User::where('name', $request->name)
                          ->where('email', $request->email)
                          ->exists();

            if ($exists) {
                $validator->errors()->add('name', 'そのユーザーはすでに存在します。');
            }
        });

        // バリデーション実行
        $validator->validate();

        // ユーザー登録
        $user = User::create([
            'name' => $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
        ]);

        // ログイン
        Auth::login($user);

        return redirect()->route('profile.edit');
    }
}
