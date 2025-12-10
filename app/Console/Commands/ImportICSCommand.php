<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use App\Services\ICSImportService;
use Illuminate\Support\Facades\Log;
use Exception;

class ImportICSCommand extends Command
{
    /**
     * Nome comando (usato nel cron)
     */
    protected $signature = 'dukares:import-ics';

    /**
     * Descrizione del comando
     */
    protected $description = 'Importa automaticamente gli ICS delle proprietà per sincronizzare disponibilità';

    /**
     * Esecuzione comando
     */
    public function handle()
    {
        $this->info("----------------------------------------------------");
        $this->info("   DukaRes ICS AutoSync - Avvio importazione ICS   ");
        $this->info("----------------------------------------------------");

        // Prendiamo le proprietà che hanno un URL ICS configurato
        $properties = Property::whereNotNull('ics_url')->get();

        if ($properties->isEmpty()) {
            $this->warn("⚠️ Nessuna proprietà con ICS configurato.");
            return Command::SUCCESS;
        }

        $service = new ICSImportService();

        foreach ($properties as $property) {
            $this->line("📌 Import ICS per proprietà: {$property->id} ({$property->nome})");

            try {
                // Scarico ICS remoto
                $icsContent = @file_get_contents($property->ics_url);

                if (!$icsContent) {
                    $this->error("❌ Impossibile scaricare ICS da {$property->ics_url}");
                    Log::error("ICS ERROR: download fallito per property {$property->id}");
                    continue;
                }

                // Importo ICS usando il servizio
                $result = $service->importICS($property->id, $icsContent, true); // true = modalità autosync

                $this->info("✔️ Importati {$result['imported']} eventi");

                Log::info("ICS IMPORT SUCCESS | Property {$property->id} | Imported {$result['imported']}");

            } catch (Exception $e) {
                $this->error("❌ Errore durante import ICS: " . $e->getMessage());
                Log::error("ICS IMPORT ERROR | Property {$property->id} | " . $e->getMessage());
            }

            sleep(1); // piccolo delay evita blocchi server
        }

        $this->info("----------------------------------------------------");
        $this->info("  ✔️ DukaRes ICS AutoSync COMPLETATO correttamente");
        $this->info("----------------------------------------------------");

        return Command::SUCCESS;
    }
}
