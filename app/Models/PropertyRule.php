<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyRule extends Model
{
    protected $fillable = [
        'property_id',
        'checkin_from',
        'checkin_to',
        'checkout_from',
        'checkout_to',
        'children_allowed',
        'pets_policy',
        'smoking_allowed',
        'min_age',
        'additional_rules',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
