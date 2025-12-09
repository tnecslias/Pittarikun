<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['user_id', 'storage_id', 'quantity'];

    public function storage()
    {
        return $this->belongsTo(Storage::class);
    }
}

