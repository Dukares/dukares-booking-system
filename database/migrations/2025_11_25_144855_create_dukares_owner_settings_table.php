<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dukares_owner_settings', function (Blueprint $table) {
            $table->id();

            // Dati bancari tuoi
            $table->string('iban')->nullable();
            $table->string('intestatario_conto')->nullable();
            $table->string('swift')->nullable();

            // Alternative
            $table->string('paypal_email')->nullable();
            $table->string('revolut')->nullable();
            $table->string('wise')->nullable();

            // Commissioni
            $table->decimal('commissione_percentuale', 5, 2)->default(15.00); // 15%
            $table->decimal('commissione_minima', 8, 2)->default(0);
            $table->decimal('commissione_massima', 8, 2)->nullable();

            // Ciclo fatturazione
            $table->enum('ciclo_fatturazione', ['settimanale', 'quindicinale', 'mensile'])
                  ->default('mensile');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dukares_owner_settings');
    }
};
