<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('terminals', function (Blueprint $table) {
            $table->after('numbering_range_id', function (Blueprint $table) {
                $table->unsignedBigInteger('factus_numbering_range_id')->nullable();
            });
        });
    }

   public function down()
    {
        Schema::table('terminals', function (Blueprint $table) {
            $table->dropColumn('factus_numbering_range_id');
        });
    }
};
