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
        if (!Schema::hasTable('penilaians')) {
            return;
        }

        Schema::table('detail_penilaians', function (Blueprint $table) {
            if (!Schema::hasColumn('detail_penilaians', 'penilaian_id')) {
                $table->unsignedBigInteger('penilaian_id')->nullable()->after('id');
                $table->foreign('penilaian_id')->references('id')->on('penilaians')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('detail_penilaians', 'penilaian_id')) {
            return;
        }

        Schema::table('detail_penilaians', function (Blueprint $table) {
            try {
                $table->dropForeign(['penilaian_id']);
            } catch (\Throwable $th) {
                // Column may exist without a foreign key if the migration was partially applied.
            }

            $table->dropColumn('penilaian_id');
        });
    }
};
