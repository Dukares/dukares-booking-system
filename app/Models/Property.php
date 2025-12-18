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
        'title',
        'description',
        'city',
        'price_per_night',
        'active',
        'ics_url',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}
