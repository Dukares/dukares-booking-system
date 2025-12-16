<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'date',
        'status',
        'source',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function property()
    {
        return $this->belongsTo(\App\Models\Property::class);
    }
}
