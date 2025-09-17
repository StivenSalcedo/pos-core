<?php

use App\Enums\TypesProviders;
use App\Models\Provider;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up() {
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('no_identification')->unique();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('direction')->nullable();
            $table->enum('type', TypesProviders::getCases())->nullable();
            $table->string('description')->nullable();
            $table->enum('status', [0, 1])->comment('0 activado, 1 desactivado');
            $table->timestamps();
        });
    }


    public function down(){
        Schema::dropIfExists('providers');
    }
};
