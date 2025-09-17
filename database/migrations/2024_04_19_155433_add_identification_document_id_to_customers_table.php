<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->after('id', function (Blueprint $table) {
                $table->foreignId('identification_document_id')->default(3)->constrained();
            });
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('identification_document_id');
        });
    }
};
