<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_rules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('property_id')->constrained()->onDelete('cascade');

            // Orari check-in
            $table->string('checkin_from')->nullable(); 
            $table->string('checkin_to')->nullable();

            // Orari check-out
            $table->string('checkout_from')->nullable();
            $table->string('checkout_to')->nullable();

            // Bambini
            $table->boolean('children_allowed')->default(true);

            // Animali
            $table->enum('pets_policy', ['yes', 'no', 'on_request'])->default('no');

            // Fumo
            $table->boolean('smoking_allowed')->default(false);

            // Età minima check-in
            $table->integer('min_age')->nullable();

            // Note aggiuntive stile Booking (facoltative)
            $table->text('additional_rules')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_rules');
    }
};
