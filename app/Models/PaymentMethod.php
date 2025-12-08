<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'nome',
        'tipo',
        'enabled'
    ];

    public function reservationPayments()
    {
        return $this->hasMany(ReservationPayment::class);
    }
}
