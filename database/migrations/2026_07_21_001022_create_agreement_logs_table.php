<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agreement_logs', function (Blueprint $table) {

            $table->id();

            $table->foreignId('job_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('worker_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->longText('agreement_content');

            $table->ipAddress('ip_address')->nullable();

            $table->string('user_agent')->nullable();

            $table->timestamp('agreed_at');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agreement_logs');
    }
};