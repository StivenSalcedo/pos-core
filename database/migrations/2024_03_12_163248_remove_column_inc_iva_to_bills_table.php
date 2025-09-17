<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn('inc');
            $table->dropColumn('iva');
            $table->dropColumn('has_inc');
            $table->dropColumn('has_iva');
        });
    }

    public function down()
    {
        Schema::table('bills', function (Blueprint $table) {
            
        });
    }
};
