<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('channel_connections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('property_id')
                ->constrained('properties')
                ->onDelete('cascade');

            $table->foreignId('channel_id')
                ->constrained('channels')
                ->onDelete('cascade');

            // ID annuncio sul portale esterno (es. Airbnb listing ID)
            $table->string('external_listing_id')->nullable();

            // URL ICS per sincronizzare calendario (per canali di tipo ICS)
            $table->string('ics_url')->nullable();

            // Stato collegamento: disconnected, connected, paused
            $table->string('status')->default('disconnected');

            $table->timestamp('last_sync_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('channel_connections');
    }
};
