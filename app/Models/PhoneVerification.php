<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PhoneVerification extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'code',
        'expires_at',
        'used',
        'attempts',
        'ip',
        'user_agent',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

