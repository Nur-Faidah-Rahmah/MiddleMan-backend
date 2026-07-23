<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->text('dispute_reason')->nullable()->after('completed_at');
            $table->unsignedBigInteger('disputed_by')->nullable()->after('dispute_reason');
            $table->timestamp('disputed_at')->nullable()->after('disputed_by');
            $table->text('dispute_notes')->nullable()->after('disputed_at');
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn(['dispute_reason', 'disputed_by', 'disputed_at', 'dispute_notes']);
        });
    }
};
