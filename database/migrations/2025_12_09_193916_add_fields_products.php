<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->after('has_presentations', function ($table) {
                $table->unsignedInteger('wholesale_price')->default(0);          
                $table->unsignedInteger('entrepreneur_price')->default(0);
                $table->foreignId('provider_id')->nullable()->constrained('providers');// referencia a tabla terminals //
                $table->foreignId('brand_id')->nullable()->constrained('brands');
            });
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('wholesale_price');
            $table->dropColumn('entrepreneur_price');
            $table->dropForeign(['provider_id']);
            $table->dropColumn('provider_id');
            $table->dropForeign(['brand_id']);
            $table->dropColumn('brand_id');
        });

    }
};
