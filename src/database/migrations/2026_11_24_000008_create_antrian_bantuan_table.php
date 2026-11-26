<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('antrian_bantuan', function (Blueprint $table) {
            $table->foreignId('antrian_id')->constrained('antrians')->cascadeOnDelete();
            $table->foreignId('bantuan_id')->constrained('bantuans')->cascadeOnDelete();

            $table->primary(['antrian_id', 'bantuan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('antrian_bantuan');
    }
};
