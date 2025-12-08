
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Numero di telefono
            $table->string('phone', 30)->nullable()->after('email');
            $table->timestamp('phone_verified_at')->nullable()->after('phone');

            // Sicurezza / antifrode
            $table->string('last_login_ip')->nullable()->after('remember_token');
            $table->timestamp('last_login_at')->nullable()->after('last_login_ip');

            // Modalità 2FA preferita (email/app/sms)
            $table->string('two_factor_preferred')->nullable()->after('two_factor_confirmed_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'phone_verified_at',
                'last_login_ip',
                'last_login_at',
                'two_factor_preferred',
            ]);
        });
    }
};
