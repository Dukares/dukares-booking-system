<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservation_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reservation_id');
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('payment_method_id')->nullable();

            $table->decimal('importo', 10, 2);
            $table->decimal('commissione_dukares', 10, 2)->default(0);
            $table->decimal('importo_struttura', 10, 2)->default(0);

            $table->enum('stato', ['in_attesa', 'pagato', 'fallito', 'rimborsato'])->default('in_attesa');

            $table->string('transazione_id')->nullable();
            $table->json('dettagli_gateway')->nullable();

            $table->timestamps();

            // Relazioni
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_payments');
    }
};
                        
