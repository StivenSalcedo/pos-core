<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up() {
        Schema::table('bills', function (Blueprint $table) {
            $table->after('id', function(Blueprint $table){
                $table->string('number')->unique()->nullable()->default(null);
            });
        });
    }

    public function down() {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn('number');
        });
    }
};
