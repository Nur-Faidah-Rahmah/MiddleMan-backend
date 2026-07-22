<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('escrows', function (Blueprint $table) {

            $table->id();

            $table->foreignId('job_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('requester_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('worker_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->decimal('amount',12,2);

            $table->enum('status',[
                'pending',
                'funded',
                'released',
                'refunded'
            ])->default('pending');

            $table->timestamp('funded_at')->nullable();

            $table->timestamp('released_at')->nullable();

            $table->timestamp('refunded_at')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('escrows');
    }
};