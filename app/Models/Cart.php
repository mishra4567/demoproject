<?php
// app/Models/Cart.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'product_id', 'quantity', 'options'];

    protected $casts = ['options' => 'array'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
