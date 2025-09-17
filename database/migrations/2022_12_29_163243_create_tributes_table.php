<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up() {
        Schema::create('tributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->enum('status', ['0', '1'])->comment('0 activo 1 inactivo');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('tributes');
    }
};
