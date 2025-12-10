<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'is_active',
        'description',
    ];

    public function connections()
    {
        return $this->hasMany(ChannelConnection::class);
    }
}
