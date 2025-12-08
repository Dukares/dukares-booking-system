<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityDevice extends Model
{
    protected $table = 'security_devices';

    protected $fillable = [
        'user_id',
        'device_info',
        'ip_address',
        'location',
        'trusted',
        'last_login_at'
    ];

    // relazione inversa con Users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
