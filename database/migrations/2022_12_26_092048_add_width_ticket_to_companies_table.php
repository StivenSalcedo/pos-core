<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up() {
        Schema::table('companies', function (Blueprint $table) {
            $table->after('barcode', function ($table) {
                $table->unsignedInteger('width_ticket')->default(80);
            });
        });
    }

    public function down() {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('width_ticket');
        });
    }
};
