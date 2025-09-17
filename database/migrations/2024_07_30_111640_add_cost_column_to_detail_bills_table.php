<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('detail_bills', function (Blueprint $table) {
           $table->after('name', function ($table) {
                $table->unsignedInteger('cost')->default(0);
            });
        });
    }

    public function down()
    {
        Schema::table('detail_bills', function (Blueprint $table) {
            $table->dropColumn('cost');
        });
    }
};
