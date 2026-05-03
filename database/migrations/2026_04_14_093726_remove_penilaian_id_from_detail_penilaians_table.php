<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('detail_penilaians', 'penilaian_id')) {
            return;
        }

        $hasForeignKey = DB::selectOne(
            "select CONSTRAINT_NAME
             from information_schema.KEY_COLUMN_USAGE
             where TABLE_SCHEMA = database()
               and TABLE_NAME = ?
               and COLUMN_NAME = ?
               and REFERENCED_TABLE_NAME is not null
             limit 1",
            ['detail_penilaians', 'penilaian_id']
        ) !== null;

        Schema::table('detail_penilaians', function (Blueprint $table) use ($hasForeignKey) {
            if ($hasForeignKey) {
                $table->dropForeign(['penilaian_id']);
            }

            $table->dropColumn('penilaian_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('detail_penilaians', 'penilaian_id') || !Schema::hasTable('penilaians')) {
            return;
        }

        Schema::table('detail_penilaians', function (Blueprint $table) {
            $table->unsignedBigInteger('penilaian_id')->nullable()->after('id');
            $table->foreign('penilaian_id')->references('id')->on('penilaians')->onDelete('cascade');
        });
    }
};
