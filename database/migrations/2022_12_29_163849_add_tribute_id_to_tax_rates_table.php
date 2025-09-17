<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up() {
        Schema::table('tax_rates', function (Blueprint $table) {
            $table->after('status', function(Blueprint $table) {
                $table->foreignId('tribute_id')->nullable()->constrained();
            });
        });
    }

    public function down(){
        Schema::table('tax_rates', function (Blueprint $table) {
            $table->dropColumn('tribute_id');
        });
    }
};
