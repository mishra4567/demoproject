<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $table = 'sizes';

    protected $fillable = [
        'size',
        'status',

        'who_create',
        'created_by',
        'created_at',

        'who_edited',
        'edited_by',
        'edited_at',

        'who_delete',
        'deleted_at',

        'is_deleted',
    ];
}
