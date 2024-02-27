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
        Schema::create('detail_penilaians', function (Blueprint $table) {
            $table->id();
            $table->double('nilai');
            $table->boolean('kompeten');
            $table->unsignedBigInteger('penilaian_id');
            $table->foreign('penilaian_id')->references('id')->on('penilaians');
            $table->unsignedBigInteger('kompetensi_id');
            $table->foreign('kompetensi_id')->references('id')->on('kompetensis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penilaians');
    }
};
