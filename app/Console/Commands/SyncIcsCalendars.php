<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use App\Services\ICSImportService;

class SyncIcsCalendars extends Command
{
    protected $signature = 'ics:sync';
    protected $description = 'Sincronizza automaticamente i calendari ICS delle strutture';

    public function handle(ICSImportService $service): int
    {
        $this->info('Avvio sync ICS...');

        $properties = Property::whereNotNull('ics_url')
            ->where('active', true)
            ->get();

        if ($properties->isEmpty()) {
            $this->info('Nessuna struttura con ICS configurato.');
            return Command::SUCCESS;
        }

        foreach ($properties as $property) {
            $count = $service->import($property, $property->ics_url);
            $this->info("Property #{$property->id} → {$count} giorni importati");
        }

        $this->info('Sync ICS completato.');
        return Command::SUCCESS;
    }
}
