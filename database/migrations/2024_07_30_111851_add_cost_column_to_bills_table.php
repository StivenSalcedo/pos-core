<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->after('number', function ($table) {
                $table->unsignedInteger('cost')->default(0);
            });
        });
    }

    public function down()
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn('cost');
        });
    }
};
