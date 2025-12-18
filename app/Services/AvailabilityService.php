<?php

namespace App\Services;

use App\Models\Reservation;
use Carbon\Carbon;

class AvailabilityService
{
    /**
     * Check if a property is available for a date range
     */
    public function isAvailable(
        int $propertyId,
        Carbon $checkin,
        Carbon $checkout
    ): bool {
        return !Reservation::where('property_id', $propertyId)
            ->where('status', '!=', 'cancelled')
            ->whereDate('checkin', '<', $checkout)
            ->whereDate('checkout', '>', $checkin)
            ->exists();
    }
}
