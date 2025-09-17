<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up() {
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->after('id', function ($table) {
                $table->unsignedInteger('units');            
                $table->unsignedInteger('cost_unit');
            });
        });
    }

    public function down() {
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->dropColumn('units');
            $table->dropColumn('cost_unit');
        });
    }
};
