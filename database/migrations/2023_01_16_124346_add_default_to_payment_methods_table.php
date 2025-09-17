<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up() {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->after('name', function(Blueprint $table){
                $table->enum('default', ['0', '1'])->comment('1=esta por defecto');
            });
        });
    }

    public function down() {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->removeColumn('default');
        });
    }
};
