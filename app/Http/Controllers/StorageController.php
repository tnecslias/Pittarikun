<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Storage;
use Illuminate\Support\Facades\Auth;

class StorageController extends Controller
{
    /**
     * 収納スペース登録
     */
    public function submit(Request $request)
    {
        // バリデーション
        $request->validate([
            'width'  => 'required|numeric|min:1',
            'height' => 'required|numeric|min:1',
            'depth'  => 'required|numeric|min:1',
        ]);

        // 登録
        Storage::create([
            'user_id' => Auth::check() ? Auth::id() : null, // 未ログイン時は null
            'name'    => $request->name ?? '未命名',
            'width'   => $request->width,
            'height'  => $request->height,
            'depth'   => $request->depth,
        ]);

        return back()->with('success', '登録しました。');
    }

    /**
     * 収納スペース検索
     */
    public function search(Request $request)
    {
        // バリデーション（任意入力）
        $request->validate([
            'width'  => 'nullable|numeric|min:1',
            'height' => 'nullable|numeric|min:1',
            'depth'  => 'nullable|numeric|min:1',
        ]);

        // ユーザー入力を変数へ
        $userW = $request->width;
        $userH = $request->height;
        $userD = $request->depth;

        // クエリ作成
        $query = Storage::query();

        // 幅：ユーザー入力以上はNG、1cm以上余るのもNG
        if ($userW) {
            $query->where('width', '<=', $userW)
                ->whereRaw('( ? - width ) <= 1', [$userW]);
        }

        // 高さ
        if ($userH) {
            $query->where('height', '<=', $userH)
                ->whereRaw('( ? - height ) <= 1', [$userH]);
        }

        // 奥行き
        if ($userD) {
            $query->where('depth', '<=', $userD)
                ->whereRaw('( ? - depth ) <= 1', [$userD]);
        }

        // 結果取得
        $storages = $query->get();

        return view('home', compact('storages'));
    }
}