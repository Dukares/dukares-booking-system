<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('failed_logins', function (Blueprint $table) {
            $table->id();

            // Email usata nel tentativo di login (se presente)
            $table->string('email')->nullable();

            // IP da cui è arrivato il tentativo
            $table->string('ip')->nullable();

            // User Agent del browser (per capire se è bot o no)
            $table->text('user_agent')->nullable();

            // Optional: info aggiuntive per analisi futura
            $table->string('reason')->nullable(); // es. "wrong_password", "blocked_ip"

            $table->timestamps(); // created_at = momento del tentativo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('failed_logins');
    }
};
