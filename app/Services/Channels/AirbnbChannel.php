<?php

namespace App\Services\Channels;

use App\Services\ICSImportService;

class AirbnbChannel extends BaseChannel
{
    public function import()
    {
        if (!$this->property->ics_url) {
            return ['imported' => 0];
        }

        $content = @file_get_contents($this->property->ics_url);

        if (!$content) {
            return ['imported' => 0];
        }

        $ics = new ICSImportService();
        return $ics->importICS($this->property->id, $content);
    }

    public function export()
    {
        return ['export' => 'ok'];
    }

    public function pushAvailability()
    {
        return ['status' => 'not_supported'];
    }
}
