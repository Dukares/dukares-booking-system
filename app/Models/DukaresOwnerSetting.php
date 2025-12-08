<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DukaresOwnerSetting extends Model
{
    protected $fillable = [
        'iban',
        'intestatario_conto',
        'swift',
        'paypal_email',
        'revolut',
        'wise',
        'commissione_percentuale',
        'commissione_minima',
        'commissione_massima',
        'ciclo_fatturazione'
    ];
}
