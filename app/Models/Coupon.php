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
        'statusupdate_by',
        'statusupdate_at',
        'is_deleted',
        'who_delete',
        'deleted_at',
        'is_vendor',
        'who_create',
        'created_by',
        'created_at',
        'who_edited',
        'updated_at',
        'edited_by',
        'edited_at',
        'expiry',
    ];
}
