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
        // Tabel ini akan menyimpan pasangan calon ketua dan wakil OSIS
        Schema::create('osis_candidates', function (Blueprint $table) {
            $table->id();
            $table->string('name_ketua'); // Nama Calon Ketua
            $table->string('name_wakil'); // Nama Calon Wakil
            $table->string('photo_ketua')->nullable(); // Path foto ketua, bisa null
            $table->string('photo_wakil')->nullable(); // Path foto wakil, bisa null
            $table->text('vision'); // Visi
            $table->text('mission'); // Misi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('osis_candidates');
    }
};
