<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceLogin extends Model
{
    protected $fillable = [
        'user_id',
        'browser',
        'os',
        'ip',
        'last_used',
        'risk_level',
        'is_blocked',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
