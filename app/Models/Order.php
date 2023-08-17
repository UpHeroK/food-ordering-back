<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'total',
        'address',
        'phone',
        'user_id',
    ];

    public function products() {
        return $this->belongsTo(OrderProduct::class);
    }
}
