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
        Schema::create('pakets', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('AlokasiWaktu');
            $table->string('BentukPenugasan');
            $table->string('JudulTugas');
            $table->unsignedBigInteger('jurusan_id');
            $table->foreign('jurusan_id')->references('id')->on('jurusans');
            $table->unsignedBigInteger('tahunajaran_id');
            $table->foreign('tahunajaran_id')->references('id')->on('tahun_ajarans');

            $table->unsignedBigInteger('aksesoreksternal');
            $table->foreign('aksesoreksternal')->references('id')->on('aksesors');

            $table->unsignedBigInteger('aksesorinternal');
            $table->foreign('aksesorinternal')->references('id')->on('aksesors');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pakets');
    }
};
