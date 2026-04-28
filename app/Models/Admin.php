<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'admin_role',
        'status',
        'admin_appr',
        'email_verification_token',
        'email_verified_at',
        'password_reset_token',
        'password_reset_expires_at',
    ];
}
