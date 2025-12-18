<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'guest_name',
        'checkin',
        'checkout',
        'status',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
