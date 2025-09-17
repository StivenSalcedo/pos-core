<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up() {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('no_identification')->unique();
            $table->string('names');
            $table->string('direction')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->enum('top', [0, 1])->comment('0 destacado, 1 no destacado');
            $table->enum('status', [0, 1])->comment('0 activado, 1 desactivado');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('customers');
    }
};
