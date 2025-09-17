<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('factus_configurations', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_api_enabled');
            $table->json('api');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('factus_configurations');
    }
};
