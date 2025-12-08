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
    ];

    /**
     * Relazione: una struttura appartiene ad un proprietario
     */
    public function owner()
    {
        return $this->belongsTo(Owner::class, 'owner_id');
    }

    /**
     * Relazione: calendario PMS
     */
    public function calendar()
    {
        return $this->hasMany(PropertyCalendar::class, 'property_id');
    }
}
