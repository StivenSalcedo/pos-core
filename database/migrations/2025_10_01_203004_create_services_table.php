<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            // core fields
            $table->date('date_entry')->nullable(false);
            $table->date('date_due')->nullable(false);
            $table->string('document_number')->nullable();
            
            // responsables / técnico
            $table->foreignId('responsible_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('tech_assigned_id')->nullable()->constrained('staff')->nullOnDelete();

            // cliente & equipo
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('equipment_type_id')->nullable()->constrained('equipment_types')->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->cascadeOnDelete();

            // details
            $table->string('model')->nullable(false);
            $table->string('user')->nullable();
            $table->string('password')->nullable();
            $table->string('accessories')->nullable();
            $table->text('problem_description')->nullable();
            $table->text('diagnosis')->nullable();
            $table->date('estimated_delivery')->nullable();
            $table->string('serial')->nullable();

            // estado (nullable por si se crea después)
            $table->foreignId('state_id')->nullable()->constrained('service_states')->nullOnDelete()->default(NULL);

            // audit
            $table->foreignId('terminal_id')->nullable()->constrained('terminals')->nullOnDelete(); // sede/terminal
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // usuario/terminal
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
