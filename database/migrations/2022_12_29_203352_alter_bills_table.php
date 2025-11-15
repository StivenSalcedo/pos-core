<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up() {
        Schema::table('bills', function (Blueprint $table) {

            $table->after('subtotal', function(Blueprint $table){
                $table->unsignedInteger('inc');
                $table->boolean('has_iva');
                $table->boolean('has_inc');
            });

            $table->renameColumn('tax', 'iva');
            
        });
    }
    
    public function down() {
        Schema::table('bills', function (Blueprint $table) {
            //$table->dropColumn('inc');
            //$table->renameColumn('iva', 'tax');
        });
    }
};
