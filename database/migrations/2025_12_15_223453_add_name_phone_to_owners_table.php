<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('owners', function (Blueprint $table) {
            if (!Schema::hasColumn('owners', 'name')) {
                $table->string('name')->nullable()->after('id');
            }

            if (!Schema::hasColumn('owners', 'email')) {
                $table->string('email')->nullable()->after('name');
            }

            if (!Schema::hasColumn('owners', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }

            // se manca timestamps
            if (!Schema::hasColumn('owners', 'created_at') && !Schema::hasColumn('owners', 'updated_at')) {
                $table->timestamps();
            }
        });
    }

    public function down(): void
    {
        Schema::table('owners', function (Blueprint $table) {
            if (Schema::hasColumn('owners', 'phone')) $table->dropColumn('phone');
            if (Schema::hasColumn('owners', 'email')) $table->dropColumn('email');
            if (Schema::hasColumn('owners', 'name'))  $table->dropColumn('name');
        });
    }
};
