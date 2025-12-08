<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Aggiunge il campo "role" nella tabella users.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Aggiungiamo la colonna role solo se non esiste già
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')
                      ->default('host')   // <- ruolo predefinito
                      ->after('password');
            }
        });
    }

    /**
     * Rimuove il campo "role" in caso di rollback.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};

