<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up() {
        Schema::create('presentations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('price');
            $table->unsignedInteger('quantity');
            $table->enum('status', [0, 1])->comment('0 activado, 1 desactivado');
            
            $table->foreignId('product_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(){
        Schema::dropIfExists('presentations');
    }
};
