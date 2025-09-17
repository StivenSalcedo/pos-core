<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up() {
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('amount');
            $table->unsignedInteger('cost');
            $table->unsignedInteger('total');
            $table->foreignId('product_id')->constrained();
            $table->foreignId('purchase_id')->constrained();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('purchase_details');
    }
};
