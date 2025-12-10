<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChannelConnection extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'channel_id',
        'external_listing_id',
        'ics_url',
        'status',
        'last_sync_at',
    ];

    protected $casts = [
        'last_sync_at' => 'datetime',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
}
