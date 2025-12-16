<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'name',
        'capacity',
        'price',
        'active',
    ];

    public function property()
    {
        return $this->belongsTo(\App\Models\Property::class);
    }
}
