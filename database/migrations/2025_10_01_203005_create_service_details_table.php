<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('component_id')->constrained('components')->cascadeOnDelete();

            // optional brand for component
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();

            $table->string('reference')->default('SIN REFERENCIA');
            $table->string('capacity')->default('N/A');
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 18, 2)->nullable();
            $table->decimal('total', 18, 2)->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_details');
    }
};
