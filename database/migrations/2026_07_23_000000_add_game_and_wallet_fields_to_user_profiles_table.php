<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->decimal('wallet_balance', 14, 2)->default(5000000.00);
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->integer('level')->default(1);
            $table->integer('exp')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn(['wallet_balance', 'rating', 'level', 'exp']);
        });
    }
};
