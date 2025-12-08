<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'property_id',
        'room_id',
        'nome_ospite',
        'email',
        'telefono',
        'checkin',
        'checkout',
        'numero_adulti',
        'numero_bambini',
        'prezzo_totale',
        'stato',
        'note'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
