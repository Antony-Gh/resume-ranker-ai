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
        Schema::table('subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('subscriptions', 'plan_id')) {
                $table->string('plan_id')->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('subscriptions', 'starts_at')) {
                $table->timestamp('starts_at')->nullable()->after('stripe_price');
            }

            if (!Schema::hasColumn('subscriptions', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('starts_at');
            }

            if (!Schema::hasColumn('subscriptions', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('expires_at');
            }

            if (!Schema::hasColumn('subscriptions', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('cancelled_at');
            }

            if (!Schema::hasColumn('subscriptions', 'payment_id')) {
                $table->string('payment_id')->nullable()->after('payment_method');
            }

            if (!Schema::hasColumn('subscriptions', 'amount')) {
                $table->decimal('amount', 10, 2)->nullable()->after('payment_id');
            }

            if (!Schema::hasColumn('subscriptions', 'currency')) {
                $table->string('currency', 3)->default('USD')->after('amount');
            }

            if (!Schema::hasColumn('subscriptions', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'plan_id',
                'starts_at',
                'expires_at',
                'cancelled_at',
                'payment_method',
                'payment_id',
                'amount',
                'currency',
                'deleted_at',
            ]);
        });
    }
};
