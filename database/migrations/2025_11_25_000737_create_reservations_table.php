<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            // Collegamento struttura
            $table->foreignId('property_id')->constrained()->onDelete('cascade');

            // TEMP: rimuoviamo la room_id (verrà reintrodotta quando costruiamo le camere)
            $table->unsignedBigInteger('room_id')->nullable();

            // Ospite
            $table->string('nome_ospite');
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();

            // Date soggiorno
            $table->date('checkin');
            $table->date('checkout');

            // Prezzi
            $table->integer('numero_adulti')->default(1);
            $table->integer('numero_bambini')->default(0);
            $table->decimal('prezzo_totale', 10, 2)->nullable();

            // Stato prenotazione
            $table->enum('stato', [
                'confermato',
                'in_arrivo',
                'in_corso',
                'completato',
                'cancellato'
            ])->default('confermato');

            // Note
            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
