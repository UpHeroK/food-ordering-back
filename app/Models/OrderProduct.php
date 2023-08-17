<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'product_id',
        'order_id',
    ];

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function products() {
        return $this->hasMany(Products::class);
    }
}
