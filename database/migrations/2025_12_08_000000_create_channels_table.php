<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->string('name');              // Es. "Booking.com"
            $table->string('slug')->unique();    // es. "booking", "airbnb"
            $table->string('type')->default('ics'); 
            // 'ics' = calendario (Google, Airbnb ICS)
            // 'api' = integrazione API in futuro
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};
