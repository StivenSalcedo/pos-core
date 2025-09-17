<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up() {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('tax');
            $table->unsignedInteger('discount');
            $table->unsignedInteger('total');
            $table->enum('status', [0, 1])->comment('0 activa y 1 anulada');
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('payment_method_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bills');
    }
};
