<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('antrian_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('antrian_id')->constrained('antrians')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status_dari')->nullable();
            $table->string('status_ke');
            $table->string('catatan')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['antrian_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('antrian_logs');
    }
};
