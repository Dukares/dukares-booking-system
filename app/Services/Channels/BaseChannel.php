<?php

namespace App\Services\Channels;

abstract class BaseChannel
{
    protected $property;

    public function __construct($property)
    {
        $this->property = $property;
    }

    // Import prenotazioni
    abstract public function import();

    // Export prenotazioni/disponibilità
    abstract public function export();

    // Invio disponibilità ai portali (quando avrai API Booking/Vrbo)
    abstract public function pushAvailability();
}
