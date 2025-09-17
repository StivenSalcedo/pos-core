<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('numbering_ranges', function (Blueprint $table) {
            $table->after('resolution_number', function(Blueprint $table){
                $table->date('date_authorization')->default('2020-01-01');
            });
        });
    }

    public function down()
    {
        Schema::table('numbering_ranges', function (Blueprint $table) {
            $table->dropColumn('date_authorization');
        });
    }
};
