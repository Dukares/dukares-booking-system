<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $table = 'properties';

    protected $fillable = [
        'owner_id',
        'name',
        'description',
        'address',
        'city',
        'country',
        'stars',
        'property_type',
        'ics_url',   // <-- AGGIUNTO: URL ICS per Airbnb / Booking / VRBO
    ];

    /**
     * Relazione: una struttura appartiene a un proprietario
     */
    public function owner()
    {
        return $this->belongsTo(Owner::class, 'owner_id');
    }

    /**
     * Relazione: calendario PMS (giorni)
     */
    public function calendarDays()
    {
        return $this->hasMany(\App\Models\CalendarDay::class, 'property_id');
    }

    /**
     * Relazione: Channel Manager
     * (collegamenti con Airbnb, Google Calendar, Booking, etc)
     */
    public function channelConnections()
    {
        return $this->hasMany(\App\Models\ChannelConnection::class, 'property_id');
    }
}
