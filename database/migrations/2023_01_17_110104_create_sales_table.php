<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up() {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quantity');
            $table->unsignedBigInteger('units');
            $table->unsignedBigInteger('total');
            $table->foreignId('product_id')->constrained();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('sales');
    }
};
