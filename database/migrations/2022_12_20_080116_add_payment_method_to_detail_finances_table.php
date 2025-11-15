<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration { 
    
    public function up() {
        Schema::table('detail_finances', function (Blueprint $table) {
            $table->after('value', function ($table) {
                $table->foreignId('payment_method_id')->constrained();
            });
        });
    }

    public function down() {
        Schema::table('detail_finances', function (Blueprint $table) {
             $table->dropForeign(['payment_method_id']);
            $table->dropColumn('payment_method_id');
        });
    }
};
