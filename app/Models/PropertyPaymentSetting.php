<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyPaymentSetting extends Model
{
    protected $fillable = [
        'property_id',
        'accetta_contanti',
        'accetta_pos',
        'accetta_bonifico',
        'online_enabled',
        'gateway',
        'api_key_public',
        'api_key_secret',
        'anticipo_percentuale'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
