<?php

use App\Models\Log;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up() {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->enum('level', [Log::ERROR, Log::CRITICAL]);
            $table->json('data');
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('logs');
    }
};
