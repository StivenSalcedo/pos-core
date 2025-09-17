<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxRatesTable extends Migration{

    public function up() {
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('rate', 4, 2, true);
            $table->boolean('default');
            $table->enum('status', [0, 1])->comment('0 activado, 1 desactivado');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('tax_rates');
    }
}
