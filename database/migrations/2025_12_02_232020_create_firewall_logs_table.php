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
        Schema::create('firewall_logs', function (Blueprint $table) {
            $table->id();

            // 🔥 Info sull'IP sospetto
            $table->string('ip', 45)->index();

            // 🔥 Tipo di attacco (SQLi, XSS, Rate Limit, ecc.)
            $table->string('attack_type');

            // 🔥 Rotta o URL chiamata
            $table->string('path')->nullable();

            // 🔥 User agent (browser)
            $table->text('user_agent')->nullable();

            // 🔥 Parametri inviati
            $table->text('payload')->nullable();

            // 🔥 Messaggio dettagliato
            $table->text('message')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('firewall_logs');
    }
};
