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
        $storage->perfect_fit_count = 0;

        $storage->remaining_width = 0;
        $storage->remaining_height = 0;
        $storage->remaining_depth = 0;


        // 幅計算
        if ($spaceWidth !== null && $storage->width > 0) {

            $storage->fit_count =
                intdiv($spaceWidth, $storage->width);

            $storage->remaining_width =
                $spaceWidth % $storage->width;

            if ($storage->remaining_width === 0) {
                $storage->perfect_fit_count++;
            }
        }


// 高さ（ケース1個で計算）
if ($request->filled('height') && $storage->height > 0)
{
    $spaceHeight = (int)$request->height;

    if ($spaceHeight >= $storage->height) {

        $storage->remaining_height =
            $spaceHeight - $storage->height;

        if ($storage->remaining_height === 0) {
            $storage->perfect_fit_count++;
        }

    } else {

        // 入らない場合
        $storage->remaining_height = null;

    }
}

// 奥行き（ケース1個で計算）
if ($request->filled('depth') && $storage->depth > 0)
{
    $spaceDepth = (int)$request->depth;

    if ($spaceDepth >= $storage->depth) {

        $storage->remaining_depth =
            $spaceDepth - $storage->depth;

        if ($storage->remaining_depth === 0) {
            $storage->perfect_fit_count++;
        }

    } else {

        // 入らない場合
        $storage->remaining_depth = null;

    }
}
    }

    if ($request->filled('width') || $request->filled('height') || $request->filled('depth')) {
        $storages = $storages->sort(function ($a, $b) use ($request) {
            // 1) ぴったり一致数が多い順
            $cmp = $b->perfect_fit_count <=> $a->perfect_fit_count;
            if ($cmp !== 0) {
                return $cmp;
            }

            // 2) 同数の場合は各残り値でタイブレーク（小さい順）
            if ($request->filled('width')) {
                $cmp = ($a->remaining_width ?? 9999) <=> ($b->remaining_width ?? 9999);
                if ($cmp !== 0) {
                    return $cmp;
                }
            }

            if ($request->filled('depth')) {
                $cmp = ($a->remaining_depth ?? 9999) <=> ($b->remaining_depth ?? 9999);
                if ($cmp !== 0) {
                    return $cmp;
                }
            }

            if ($request->filled('height')) {
                $cmp = ($a->remaining_height ?? 9999) <=> ($b->remaining_height ?? 9999);
                if ($cmp !== 0) {
                    return $cmp;
                }
            }

            return 0;
        })->values();
    }


    return view('home', compact('storages'));
}
}
