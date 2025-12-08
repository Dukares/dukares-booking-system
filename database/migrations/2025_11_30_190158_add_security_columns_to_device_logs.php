<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('device_logs', function (Blueprint $table) {
            $table->timestamp('last_used_at')->nullable()->after('logged_in_at');
            $table->unsignedTinyInteger('risk_level')->default(0)->after('last_used_at');
            $table->boolean('is_suspicious')->default(false)->after('risk_level');
        });
    }

    public function down(): void
    {
        Schema::table('device_logs', function (Blueprint $table) {
            $table->dropColumn('last_used_at');
            $table->dropColumn('risk_level');
            $table->dropColumn('is_suspicious');
        });
    }
};
