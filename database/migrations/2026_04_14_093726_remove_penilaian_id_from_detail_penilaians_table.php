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
        Schema::table('detail_penilaians', function (Blueprint $table) {
            $table->dropForeign(['penilaian_id']);
            $table->dropColumn('penilaian_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_penilaians', function (Blueprint $table) {
            $table->unsignedBigInteger('penilaian_id')->nullable()->after('id');
            $table->foreign('penilaian_id')->references('id')->on('penilaians')->onDelete('cascade');
        });
    }
};
