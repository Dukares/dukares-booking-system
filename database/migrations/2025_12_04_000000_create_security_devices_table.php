<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_devices', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');

            $table->string('device_info')->nullable();   // Browser + OS
            $table->string('ip_address')->nullable();
            $table->string('location')->nullable();       // GeoIP futura
            $table->boolean('trusted')->default(false);   // dispositivo fidato
            $table->timestamp('last_login_at')->nullable();

            $table->timestamps();

            // Relazione con tabella users
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_devices');
    }
};
