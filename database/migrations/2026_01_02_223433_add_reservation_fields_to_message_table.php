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
        Schema::table('message', function (Blueprint $table) {
            $table->integer('idreservation')->nullable()->after('contenumessage');
            $table->boolean('lu')->default(false)->after('idreservation');
            $table->timestamp('created_at')->nullable()->after('lu');
            
            $table->foreign('idreservation')
                ->references('idreservation')
                ->on('reservation')
                ->onDelete('cascade');
            
            $table->index('idreservation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('message', function (Blueprint $table) {
            $table->dropForeign(['idreservation']);
            $table->dropIndex(['idreservation']);
            $table->dropColumn(['idreservation', 'lu', 'created_at']);
        });
    }
};
