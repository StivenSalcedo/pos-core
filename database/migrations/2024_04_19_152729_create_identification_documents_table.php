<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('identification_documents', function (Blueprint $table) {
            $table->comment('documentos de identificacion de las personas');

            $table->id();

            $table->string('code')->unique();
            $table->string('name')->unique();
            $table->boolean('is_enabled');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('identification_documents');
    }
};
