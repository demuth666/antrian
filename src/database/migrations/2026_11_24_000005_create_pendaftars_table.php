<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftars', function (Blueprint $table) {
            $table->id();
            $table->text('nik');
            $table->string('nik_hash')->unique();
            $table->string('hp_hash')->index();
            $table->string('nama');
            $table->date('tanggal_lahir');
            $table->char('jenis_kelamin', 1);
            $table->text('no_hp');
            $table->string('alamat');
            $table->string('jenis_disabilitas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftars');
    }
};
