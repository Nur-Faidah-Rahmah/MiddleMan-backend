<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('job_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('escrow_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->decimal('amount',12,2);

            $table->enum('type',[
                'deposit',
                'release',
                'refund',
                'withdraw'
            ]);

            $table->enum('status',[
                'pending',
                'success',
                'failed'
            ])->default('pending');

            $table->text('description')->nullable();

            $table->timestamp('transaction_at');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};