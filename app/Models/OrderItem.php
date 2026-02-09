<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'storage_id',
        'price',
        'quantity',
    ];

    public function storage()
    {
        return $this->belongsTo(Storage::class);
    }
}

