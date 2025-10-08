<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->string('filename');
            $table->string('path');
            $table->string('mime')->nullable();
            $table->unsignedInteger('size')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // quien subió
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_attachments');
    }
};
