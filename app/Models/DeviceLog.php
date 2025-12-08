<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceLog extends Model
{
    protected $fillable = [
        'user_id',
        'ip',
        'browser',
        'os',
        'device_fingerprint',
        'is_trusted',
        'logged_in_at',
        'last_used_at',
        'risk_level',
        'is_suspicious'
    ];
}
