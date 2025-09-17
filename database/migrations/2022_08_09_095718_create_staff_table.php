<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up() {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('no_identification')->unique();
            $table->string('names');
            $table->string('direction')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('description')->nullable();
            $table->enum('status', [0, 1])->comment('0 activado, 1 desactivado');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('staff');
    }
};
