<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Storage;

class MujiStorageSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => 'ポリプロピレン収納ケース・引出式・小',
                'width' => 26,
                'height' => 12,
                'depth' => 37,
                'image' => 'storage_images/small.png',
            ],
            [
                'name' => 'ポリプロピレン収納ケース・引出式・大',
                'width' => 34,
                'height' => 24,
                'depth' => 44,
                'image' => 'storage_images/large.png',
            ],
            [
                'name' => 'ポリプロピレンケース・引出式 深型',
                'width' => 40,
                'height' => 30,
                'depth' => 55, 
                'image' => 'storage_images/deep.png',
            ],
            [
                'name' => 'ソフトボックス・長方形・中',
                'width' => 37,
                'height' => 26,
                'depth' => 26,
                'image' => 'storage_images/medium.png',
            ],
            [
                'name' => 'ソフトボックス・長方形・大',
                'width' => 37,
                'height' => 32,
                'depth' => 26,
                'image' => 'storage_images/large_softbox.png',
            ],
        ];

        foreach ($items as $item) {
            Storage::create([
                'user_id' => null,
                'name' => $item['name'],
                'width' => $item['width'],
                'height' => $item['height'],
                'depth' => $item['depth'],
                'image' => $item['image'],
            ]);
        }
    }
}
