<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('payment_method_id')->nullable()->after('product_id');
            $table->foreignId('user_id')->nullable()->after('payment_method_id');
            $table->foreignId('bill_id')->nullable()->after('user_id');
            $table->foreignId('service_id')->nullable()->after('bill_id');
            $table->string('source')->after('service_id');

            $table->index(['created_at', 'product_id']);
            $table->index(['payment_method_id']);
            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_method_id');
            $table->dropConstrainedForeignId('user_id');
            $table->dropConstrainedForeignId('bill_id');
            $table->dropConstrainedForeignId('service_id');
            $table->dropColumn('source');
        });
    }
};
