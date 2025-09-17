<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration  {

    public function up() {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('total');
            $table->enum('status', [0, 1])->comment('0 activo, 1 Anulado');
            $table->foreignId('provider_id')->constrained();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('purchases');
    }
};
