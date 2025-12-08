<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationPayment extends Model
{
    protected $fillable = [
        'reservation_id',
        'property_id',
        'payment_method_id',
        'importo',
        'commissione_dukares',
        'importo_struttura',
        'stato',
        'transazione_id',
        'dettagli_gateway'
    ];

    protected $casts = [
        'dettagli_gateway' => 'array',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
