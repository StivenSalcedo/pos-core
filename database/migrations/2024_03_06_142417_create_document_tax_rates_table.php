<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('document_tax_rates', function (Blueprint $table) {
            $table->id();

            $table->boolean('has_percentage')->comment('1 si el impuesto se cobro en porcentaje si no en pesos');
            $table->unsignedDecimal('rate', 8, 2)->comment('valor en pesos o porcentaje del impuesto');
            $table->unsignedDecimal('taxable_amount', 12)->comment('base imponible');
            $table->unsignedDecimal('tax_amount', 12)->comment('valor del impuesto');

            $table->foreignId('document_tax_id')->constrained();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('document_tax_rates');
    }
};
