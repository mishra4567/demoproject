<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicalSpecs extends Model
{
    protected $table = 'technical_specs';

    protected $fillable = [
        'title',
        'product_id',

        'lead_time_from',
        'lead_time_to',

        'tax',
        'tax_type',
        'custom_tax_type',

        'is_promo',
        'is_featured',
        'is_discounted',
        'is_trending',

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
