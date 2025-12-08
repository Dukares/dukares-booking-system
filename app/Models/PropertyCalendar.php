<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyCalendar extends Model
{
    protected $table = 'property_calendar';

    protected $fillable = [
        'property_id',
        'date',
        'status',
        'price',
        'min_stay',
        'source'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
