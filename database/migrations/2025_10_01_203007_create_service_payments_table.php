<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->decimal('amount', 18, 2);
            $table->string('method')->nullable(); // ej: efectivo, tarjeta, transferencia
            $table->string('reference')->nullable(); // numero de transacción
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // quien registró el pago
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_payments');
    }
};
