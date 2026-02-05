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
        $query = Storage::query();

        if ($request->filled('width')) {
            $query->where('width', '<=', (int)$request->width);
        }

        if ($request->filled('height')) {
            $query->where('height', '<=', (int)$request->height);
        }

        if ($request->filled('depth')) {
            $query->where('depth', '<=', (int)$request->depth);
        }

        $storages = $query->get();

        return view('home', compact('storages'));
    }


}