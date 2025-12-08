<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('ip')->nullable();
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->string('device_fingerprint')->nullable();

            $table->boolean('is_trusted')->default(false);

            $table->timestamp('logged_in_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_logs');
    }
};
