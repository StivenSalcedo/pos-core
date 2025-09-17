<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cash_closings', function (Blueprint $table) {
            $table->after('total_sales', function (Blueprint $table) {
                $table->unsignedInteger('tip')->default(0);
            });
        });
    }

    public function down()
    {
        Schema::table('cash_closings', function (Blueprint $table) {
            $table->dropColumn('tip');
        });
    }
};
