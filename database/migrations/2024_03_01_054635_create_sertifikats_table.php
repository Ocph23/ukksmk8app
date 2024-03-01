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
        Schema::create('sertifikats', function (Blueprint $table) {
            $table->id();
            $table->string('instansi');
            $table->string('ketuapenguji');
            $table->string('nomorseri');
            $table->string('nomor');
            $table->date('tanggalpenetapan');
            $table->date('tanggalcetak')->nullable(true);
            $table->date('tanggalambil')->nullable(true);
            $table->string('diambiloleh')->nullable(true);
            $table->unsignedBigInteger('siswa_id');
            $table->foreign('siswa_id')->references('id')->on('siswas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikats');
    }
};
