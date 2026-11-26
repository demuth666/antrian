<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('antrians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained('pendaftars')->cascadeOnDelete();
            $table->foreignId('layanan_id')->constrained('layanans')->restrictOnDelete();
            $table->foreignId('loket_id')->nullable()->constrained('lokets')->nullOnDelete();
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('tanggal');
            $table->unsignedInteger('nomor');
            $table->char('kode', 26)->unique();
            $table->string('kategori');
            $table->string('status')->default('menunggu');
            $table->string('nama_pendamping')->nullable();
            $table->timestamp('dipanggil_at')->nullable();
            $table->timestamp('mulai_dilayani_at')->nullable();
            $table->timestamp('selesai_at')->nullable();
            $table->timestamps();

            $table->unique(['layanan_id', 'tanggal', 'nomor']);
            $table->index(['tanggal', 'status']);
            $table->index(['pendaftar_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('antrians');
    }
};
