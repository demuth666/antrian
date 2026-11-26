<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('petugas')->after('password');
            $table->foreignId('loket_id')->nullable()->after('role')->constrained('lokets')->nullOnDelete();
            $table->boolean('aktif')->default(true)->after('loket_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('loket_id');
            $table->dropColumn(['role', 'aktif']);
        });
    }
};
