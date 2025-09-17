<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up() {
        Schema::table('detail_finances', function (Blueprint $table) {
            $table->after('value', function(Blueprint $table){
                $table->foreignId('terminal_id')->default(1)->constrained();
            });
        });
    }

    public function down() {
        Schema::table('detail_finances', function (Blueprint $table) {
            $table->dropColumn('terminal_id');
        });
    }
};
