<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up() {
        Schema::table('detail_bills', function (Blueprint $table) {
            $table->after('discount', function(Blueprint $table){
                $table->string('tribute');
            });
        });
    }

    public function down() {
        Schema::table('detail_bills', function (Blueprint $table) {
            $table->dropColumn('tribute');
        });
    }
};
