<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up() {
        Schema::create('outputs', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('reason')->comment('Motivo por el cual saca el dinero');
            $table->unsignedInteger('price')->nullable();
            $table->string('description')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('outputs');
    }
};
