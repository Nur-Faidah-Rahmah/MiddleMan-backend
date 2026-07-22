<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verification_documents', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('document_name');

            $table->string('document_path');

            $table->enum('document_type',[
                'certificate',
                'portfolio',
                'identity',
                'other'
            ]);

            $table->enum('status',[
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            $table->text('review_note')->nullable();

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('verified_at')->nullable();

            $table->timestamps();

            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verification_documents');
    }
};