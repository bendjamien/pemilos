<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mpk_candidates', function (Blueprint $table) {
            $table->id();
            $table->string('name_ketua'); // Nama Calon Ketua (Wajib)
            $table->string('name_wakil')->nullable(); // Nama Calon Wakil (Opsional)
            $table->string('photo_ketua')->nullable(); // Foto Ketua
            $table->string('photo_wakil')->nullable(); // Foto Wakil (Opsional)
            $table->text('vision');
            $table->text('mission');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpk_candidates');
    }
};
