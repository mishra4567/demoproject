<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $table = 'colors';

    protected $fillable = [
        'color_name',
        'hex_id',
        'status',
        'is_deleted',
        'is_vendor',
        'who_create',
        'who_delete',
        'deleted_at',
        'who_edited',
        'edited_by',
        'edited_at',
        'created_by',
        'statusupdate_by',
        'statusupdate_at',
    ];
}
