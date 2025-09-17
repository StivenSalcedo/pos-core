<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up() {
        Schema::create('cash_closings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('base')->comment('efectivo base');
            $table->unsignedInteger('cash')->comment('efectivo');
            $table->unsignedInteger('debit_card');
            $table->unsignedInteger('credit_card');
            $table->unsignedInteger('transfer');
            $table->unsignedInteger('financed');
            $table->unsignedInteger('outputs')->comment('egresos');
            $table->unsignedInteger('cash_register')->comment('efectivo en caja');
            $table->unsignedInteger('price')->comment('precio real en caja');
            $table->string('observations')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('cash_closings');
    }
};
