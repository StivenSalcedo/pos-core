<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(){
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('nit');
            $table->string('name');
            $table->string('direction')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->enum('type_bill', ['0', '1'])->comment('0=factura y 1=ticket');
            $table->enum('barcode', ['0', '1'])->comment('0=pistola y 1=manual');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('companies');
    }
};
