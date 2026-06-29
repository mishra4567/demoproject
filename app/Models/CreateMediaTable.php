<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreateMediaTable extends Model
{
    protected $table = 'create_media_tables';

    protected $fillable = [
        'file_name',
        'media_type',
        'tags',
        'description',
        'vendor_id',
        'status',
        'is_deleted',

        'is_vendor',

        'who_create',
        'created_by',

        'who_edited',
        'edited_by',
        'edited_at',

        'who_delete',
        'deleted_at',

        'statusupdate_by',
        'statusupdate_at',
    ];
}
