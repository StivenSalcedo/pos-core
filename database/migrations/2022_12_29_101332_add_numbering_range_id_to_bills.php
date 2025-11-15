<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up() {
        Schema::table('bills', function (Blueprint $table) {
            $table->after('payment_method_id',  function(Blueprint $table){
                $table->foreignId('numbering_range_id')->default(1)->constrained();
            });
        });
    }

    public function down() {
        Schema::table('bills', function (Blueprint $table) {
             $table->dropForeign(['numbering_range_id']);
            $table->dropColumn('numbering_range_id');
        });
    }
};
