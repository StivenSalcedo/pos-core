<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment_type_component', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_type_id')
                  ->constrained('equipment_types')
                  ->onDelete('cascade');

            $table->foreignId('component_id')
                  ->constrained('components')
                  ->onDelete('cascade');

            $table->unsignedInteger('default_quantity')->default(1)
                  ->comment('Cantidad por defecto de este componente para el tipo de equipo');

            $table->timestamps();

            $table->unique(['equipment_type_id', 'component_id'], 'eqtype_component_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_type_component');
    }
};
