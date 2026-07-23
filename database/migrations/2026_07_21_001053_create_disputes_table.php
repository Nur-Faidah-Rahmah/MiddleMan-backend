<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disputes', function (Blueprint $table) {

            $table->id();

            $table->foreignId('job_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('requester_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('worker_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('reason');

            $table->string('attachment')->nullable();

            $table->enum('status',[
                'open',
                'review',
                'resolved'
            ])->default('open');

            $table->enum('decision',[
                'worker_win',
                'requester_win'
            ])->nullable();

            $table->text('admin_note')->nullable();

            $table->foreignId('resolved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};