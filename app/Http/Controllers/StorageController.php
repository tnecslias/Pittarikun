<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Storage;
use Illuminate\Support\Facades\Auth;

class StorageController extends Controller
{
    /**
     * 登録
     */
    public function submit(Request $request)
    {
        $request->validate([
            'width' => 'required|numeric|min:1',
            'height' => 'required|numeric|min:1',
            'depth' => 'required|numeric|min:1',
        ]);

        Storage::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'name' => $request->name ?? '未命名',
            'width' => (int)$request->width,
            'height' => (int)$request->height,
            'depth' => (int)$request->depth,
        ]);

        return back()->with('success', '登録しました。');
    }


    /**
     * 検索
     */
    public function search(Request $request)
{
    $query = Storage::query();

    $spaceWidth  = $request->filled('width')  ? (int)$request->width  : null;
    $spaceHeight = $request->filled('height') ? (int)$request->height : null;
    $spaceDepth  = $request->filled('depth')  ? (int)$request->depth  : null;


    // 検索条件（収納ケースが入るもの）
    if ($spaceWidth !== null) {
        $query->where('width', '<=', $spaceWidth);
    }

    if ($spaceHeight !== null) {
        $query->where('height', '<=', $spaceHeight);
    }

    if ($spaceDepth !== null) {
        $query->where('depth', '<=', $spaceDepth);
    }


    $storages = $query->get();


    foreach ($storages as $storage)
    {
        // 初期化
        $storage->fit_count = 0;

        $storage->remaining_width = 0;
        $storage->remaining_height = 0;


        // 幅計算
        if ($spaceWidth !== null && $storage->width > 0) {

            $storage->fit_count =
                intdiv($spaceWidth, $storage->width);

            $storage->remaining_width =
                $spaceWidth % $storage->width;
        }


// 高さ（ケース1個で計算）
if ($request->filled('height') && $storage->height > 0)
{
    $spaceHeight = (int)$request->height;

    if ($spaceHeight >= $storage->height) {

        $storage->remaining_height =
            $spaceHeight - $storage->height;

    } else {

        // 入らない場合
        $storage->remaining_height = null;

    }
}
    }
        if ($request->filled('width')) {

        $storages = $storages->sortBy([
            fn ($s) => $s->remaining_width === 0 ? 0 : 1, 
            fn ($s) => $s->remaining_width ?? 9999,       
        ])->values();
    }


    return view('home', compact('storages'));
}
}
