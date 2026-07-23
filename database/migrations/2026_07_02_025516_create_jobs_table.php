<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {

            $table->id();

            // Requester
            $table->foreignId('requester_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Worker terpilih
            $table->foreignId('selected_worker_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Category
            $table->foreignId('category_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');

            $table->text('description');

            $table->decimal('budget',12,2);

            $table->dateTime('deadline');

            $table->enum('location_type',[
                'online',
                'offline'
            ]);

            $table->string('location')->nullable();

            $table->text('custom_terms')->nullable();

            $table->enum('status',[
                'draft',
                'waiting_payment',
                'open',
                'taken',
                'submitted',
                'approved',
                'completed',
                'disputed',
                'cancelled',
                'refunded'
            ])->default('draft');

            $table->timestamps();

            $table->timestamp('opened_at')->nullable();

            $table->timestamp('taken_at')->nullable();

            $table->timestamp('completed_at')->nullable();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};