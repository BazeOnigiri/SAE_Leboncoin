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
        Schema::table('reservation', function (Blueprint $table) {
            $table->integer('adultes')->default(1)->after('nombrevoyageur');
            $table->integer('enfants')->default(0)->after('adultes');
            $table->integer('bebes')->default(0)->after('enfants');
        });

        // Backfill existing rows: assume all nombrevoyageur are adults
        DB::table('reservation')->update([
            'adultes' => DB::raw('nombrevoyageur'),
            'enfants' => 0,
            'bebes' => 0,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservation', function (Blueprint $table) {
            $table->dropColumn(['adultes', 'enfants', 'bebes']);
        });
    }
};
