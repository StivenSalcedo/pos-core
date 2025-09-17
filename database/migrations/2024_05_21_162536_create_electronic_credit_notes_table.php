<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('electronic_credit_notes', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->text('qr_image')->nullable();
            $table->string('cude')->nullable();
            $table->boolean('is_validated')->default(0);
            $table->foreignId('bill_id')->constrained();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('electronic_credit_notes');
    }
};
