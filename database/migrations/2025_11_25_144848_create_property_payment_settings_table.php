<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_payment_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');

            // Pagamento in struttura
            $table->boolean('accetta_contanti')->default(true);
            $table->boolean('accetta_pos')->default(false);
            $table->boolean('accetta_bonifico')->default(false);

            // Pagamenti online
            $table->boolean('online_enabled')->default(false);
            $table->string('gateway')->nullable(); // stripe, paypal, revolut ecc.
            $table->string('api_key_public')->nullable();
            $table->string('api_key_secret')->nullable();

            // Politiche anticipo
            $table->integer('anticipo_percentuale')->nullable(); // 0-100

            $table->timestamps();

            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_payment_settings');
    }
};
