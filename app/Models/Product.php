<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // app/Models/Product.php
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'brand_id',
        'model',
        'price',
        'mrp',
        'media_ids',
        'coupon_id',
        'short_desc',
        'desc',
        'keywords',
        'technical_specification',
        'uses',
        'warranty',
        'status',
        'is_deleted',       // ← add
        'who_delete',       // ← add
        'deleted_at',       // ← add
        'is_vendor',
        'who_create',
        'created_by',
        'who_edited',
        'edited_by',
        'edited_at',
    ];
}
