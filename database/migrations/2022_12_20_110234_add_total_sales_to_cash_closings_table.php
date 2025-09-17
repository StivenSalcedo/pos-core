<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up() {
        Schema::table('cash_closings', function (Blueprint $table) {
            $table->after('transfer', function ($table) {
                $table->dropColumn('financed');
                $table->unsignedInteger('total_sales');
            });
        });
    }

    public function down(){
        Schema::table('cash_closings', function (Blueprint $table) {
            $table->dropColumn('total_sales');
            $table->after('transfer', function ($table) {
                $table->unsignedInteger('financed');
            });
        });
    }
};
