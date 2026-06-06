<?php
// app/Models/Coupon.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'title',
        'code',
        'value',
        'type',
        'min_order_amt',
        'is_one_time',
        'status',
        'is_deleted',       // ← add this
        'is_vendor',
        'who_create',
        'created_by',
        'who_edited',
        'edited_by',
        'edited_at',
        'expiry',
    ];
}
