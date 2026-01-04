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
        Schema::table('reservation', function (Blueprint $table) {
            if (!Schema::hasColumn('reservation', 'stripe_checkout_session_id')) {
                $table->string('stripe_checkout_session_id')->nullable();
            }
            if (!Schema::hasColumn('reservation', 'stripe_payment_intent_id')) {
                $table->string('stripe_payment_intent_id')->nullable();
            }
            if (!Schema::hasColumn('reservation', 'stripe_payment_status')) {
                $table->string('stripe_payment_status')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservation', function (Blueprint $table) {
            $cols = [];
            foreach (['stripe_checkout_session_id', 'stripe_payment_intent_id', 'stripe_payment_status'] as $col) {
                if (Schema::hasColumn('reservation', $col)) {
                    $cols[] = $col;
                }
            }

            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
