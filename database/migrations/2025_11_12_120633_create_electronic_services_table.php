<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('electronic_services', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code')->unique()->nullable();
            // Número del documento electrónico (ej: prefijo + consecutivo)
            $table->string('number')->unique();

            // Imagen QR en base64 o ruta al archivo generado
            $table->text('qr_image')->nullable();

            // Código Único de Documento Electrónico (CUDE / CUNE)
            $table->string('cufe')->nullable();
            $table->json('numbering_range')->nullable();
            // Estado de validación ante la DIAN
            $table->boolean('is_validated')->default(false)
                ->comment('0 = No validado, 1 = Validado');

            // Relación con el servicio original
            $table->foreignId('service_id')
                ->constrained()
                ->onDelete('cascade');

            // Información adicional opcional
            $table->string('xml_path')->nullable()->comment('Ruta al archivo XML generado');
            $table->string('pdf_path')->nullable()->comment('Ruta al archivo PDF generado');
            $table->json('response_dian')->nullable()->comment('Respuesta cruda del proveedor o la DIAN');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('electronic_services');
    }
};
