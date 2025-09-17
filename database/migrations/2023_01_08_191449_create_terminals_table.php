<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up() {
        Schema::create('terminals', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('numbering_range_id')->constrained();
            $table->enum('status', [0, 1])->comment('0 activa y 1 anulada');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('terminals');
    }
};
