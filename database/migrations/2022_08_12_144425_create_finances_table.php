<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up() {
        Schema::create('finances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->constrained();
            $table->enum('status', ['0', '1'])->comment('1 Pendiente, 0 pagado');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('finances');
    }
};
