<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'guest_name')) {
                $table->string('guest_name')->after('property_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'guest_name')) {
                $table->dropColumn('guest_name');
            }
        });
    }
};
