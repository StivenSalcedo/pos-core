<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tax_rates', function (Blueprint $table) {
            $table->after('name', function (Blueprint $table) {
                $table->boolean('has_percentage')->default(1);
            });
        });
    }

    public function down()
    {
        Schema::table('tax_rates', function (Blueprint $table) {
            //
        });
    }
};
