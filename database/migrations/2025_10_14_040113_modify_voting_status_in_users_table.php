<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn('has_voted');

            // Tambahkan dua kolom baru setelah kolom 'role'
            $table->boolean('has_voted_osis')->default(false)->after('role');
            $table->boolean('has_voted_mpk')->default(false)->after('has_voted_osis');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Jika migrasi di-rollback, kembalikan seperti semula
            $table->boolean('has_voted')->default(false);
            $table->dropColumn(['has_voted_osis', 'has_voted_mpk']);
        });
    }
};