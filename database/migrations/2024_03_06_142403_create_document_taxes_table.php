<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('document_taxes', function (Blueprint $table) {
            $table->id();

            $table->string('tribute_name');
            $table->unsignedDecimal('tax_amount', 12)->comment('valor del tributo (impuesto)');

            $table->morphs('document_taxeable');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('document_taxes');
    }
};
