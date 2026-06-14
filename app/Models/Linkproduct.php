<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Linkproduct extends Model
{
    protected $table = 'linkproducts';

    protected $fillable = [
        'sku',
        'mrp',
        'price',
        'qty',

        'size_id',
        'color_id',
        'product_id',
        'media_id',

        'status',
        'is_deleted',

        'is_vendor',

        'created_by',
        'who_create',

        'edited_by',
        'who_edited',
        'edited_at',

        'statusupdate_by',
        'statusupdate_at',

        'who_delete',
        'deleted_at',
    ];
}
