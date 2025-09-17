<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::table('finances', function (Blueprint $table) {
            
            $table->after('id', function(Blueprint $table){
                $table->date('due_date')->nullable();
            });

        });
    }

    public function down()
    {
        Schema::table('finances', function (Blueprint $table) {
            $table->dropColumn('due_date');
        });
    }
};
