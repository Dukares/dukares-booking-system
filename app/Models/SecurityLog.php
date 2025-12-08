<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityLog extends Model
{
    protected $table = 'security_logs';

    protected $fillable = [
        'user_id',
        'ip_address',
        'path',
        'method',
        'user_agent',
        'country',
        'level',
        'reason',
        'is_blocked',
    ];
}
