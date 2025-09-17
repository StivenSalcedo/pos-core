<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['tax_rate_id']);
            $table->dropColumn('tax_rate_id');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->after('has_presentations', function (Blueprint $table) {
                $table->foreignId('tax_rate_id')->nullable()->constrained();
            });
        });
    }
};
