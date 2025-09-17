<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up() {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('barcode')->unique();
            $table->string('reference')->unique();
            $table->string('name');
            $table->unsignedInteger('cost');
            $table->unsignedInteger('price');
            $table->unsignedInteger('stock');
            $table->unsignedInteger('quantity')->comment('cantidad en unidades por producto (obligatorio cuando tiene otras presentaciones)');
            $table->unsignedBigInteger('units')->comment('cantidad en unidades (obligatorio cuando tiene otras presentaciones)');
            $table->enum('top', ['0', '1'])->comment('0 no destacado, 1 destacado');
            $table->enum('status', ['0', '1'])->comment('0 activado, 1 desactivado');
            $table->enum('has_presentations', ['0', '1'])->comment('0=cuenta con presentaciones y 1=no cuenta con presentaciones');
            $table->foreignId('tax_rate_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(){
        Schema::dropIfExists('products');
    }
};
