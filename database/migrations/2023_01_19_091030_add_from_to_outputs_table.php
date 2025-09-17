<?php

use App\Enums\CashRegisters;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up() {
        Schema::table('outputs', function (Blueprint $table) {
            $table->after('reason', function (Blueprint $table){
                $table->enum('from', CashRegisters::getCases());
            });
        });
    }

    public function down(){
        Schema::table('outputs', function (Blueprint $table) {
            $table->dropColumn('from');
        });
    }
};
