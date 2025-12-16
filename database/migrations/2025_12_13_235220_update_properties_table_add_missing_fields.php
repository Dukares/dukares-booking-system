<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {

            if (! Schema::hasColumn('properties', 'country')) {
                $table->string('country')->nullable()->after('city');
            }

            if (! Schema::hasColumn('properties', 'stars')) {
                $table->unsignedTinyInteger('stars')->nullable()->after('country');
            }

            if (! Schema::hasColumn('properties', 'property_type')) {
                $table->string('property_type')->default('apartment')->after('stars');
            }

            if (! Schema::hasColumn('properties', 'ics_url')) {
                $table->string('ics_url')->nullable()->after('property_type');
            }

            if (! Schema::hasColumn('properties', 'active')) {
                $table->boolean('active')->default(true)->after('ics_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {

            if (Schema::hasColumn('properties', 'active')) {
                $table->dropColumn('active');
            }

            if (Schema::hasColumn('properties', 'ics_url')) {
                $table->dropColumn('ics_url');
            }

            if (Schema::hasColumn('properties', 'property_type')) {
                $table->dropColumn('property_type');
            }

            if (Schema::hasColumn('properties', 'stars')) {
                $table->dropColumn('stars');
            }

            if (Schema::hasColumn('properties', 'country')) {
                $table->dropColumn('country');
            }
        });
    }
};
