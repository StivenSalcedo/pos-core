<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up() {
        Schema::table('cash_closings', function (Blueprint $table) {

            $table->after('observations', function(Blueprint $table){
                $table->foreignId('terminal_id')->default(1)->constrained();
            });

        });
    }

    public function down() {
        Schema::table('cash_closings', function (Blueprint $table) {
            $table->dropColumn('terminal_id');
        });
    }
};
