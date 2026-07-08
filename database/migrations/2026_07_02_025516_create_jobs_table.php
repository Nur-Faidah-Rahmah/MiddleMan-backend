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
            
            // Relasi ke pembuat tugas (Customer)
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            
            // Relasi ke pengambil tugas (Worker). Nullable karena di awal belum ada yang ambil.
            $table->foreignId('worker_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Relasi ke kategori layanan
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 12, 2); // Menggunakan decimal untuk nominal uang (lebih aman)
            
            // Menggunakan ENUM untuk membatasi status yang valid
            $table->enum('status', ['pending_verification', 'approved', 'on_progress', 'completed'])
                  ->default('pending_verification');
                  
            $table->dateTime('deadline');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};