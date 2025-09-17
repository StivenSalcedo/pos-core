<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up() {
        Schema::create('numbering_ranges', function (Blueprint $table) {

            $table->id();
            $table->string('prefix');
            $table->unsignedBigInteger('from');
            $table->unsignedBigInteger('to');
            $table->unsignedBigInteger('current');
            $table->string('resolution_number')->nullable();
            $table->date('expire');
            $table->enum('status', ['0', '1'])->comment('0 activado, 1 desactivado');
            $table->softDeletes();

            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('numbering_ranges');
    }
};
