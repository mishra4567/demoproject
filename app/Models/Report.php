<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'reports';

    protected $fillable = [
        'user_type',
        'user_id',
        'auth_email',
        'auth_password',
        'user_name',
        'rating',
        'title',
        'report_data',
        'status',
    ];

    protected $casts = [
        'report_data' => 'array',
    ];
}
