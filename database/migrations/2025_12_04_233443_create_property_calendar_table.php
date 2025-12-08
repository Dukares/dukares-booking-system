<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_calendar', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('property_id');

            $table->date('date'); // giorno specifico

            // stato giornata
            $table->enum('status', ['available', 'booked', 'closed'])
                  ->default('available');

            // prezzo del giorno
            $table->decimal('price', 10, 2)->nullable();

            // min stay opzionale
            $table->unsignedInteger('min_stay')->nullable();

            // fonte: dukares, booking, airbnb, vrbo, manual...
            $table->string('source')->nullable();

            $table->timestamps();

            // impedisce doppioni
            $table->unique(['property_id', 'date']);

            // relazione
            $table->foreign('property_id')
                  ->references('id')->on('properties')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_calendar');
    }
};
