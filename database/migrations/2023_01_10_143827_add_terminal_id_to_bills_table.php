<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up() {
        Schema::table('bills', function (Blueprint $table) {
            $table->after('status', function(Blueprint $table){
                $table->foreignId('terminal_id')->default(1)->constrained();
            });
        });
    }

    public function down() {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn('terminal_id');
        });
    }
};
