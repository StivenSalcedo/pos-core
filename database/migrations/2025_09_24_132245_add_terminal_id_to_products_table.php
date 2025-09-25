<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('terminal_id')
                ->nullable()
                  ->after('category_id') // opcional, para ordenar la columna
                  ->constrained('terminals') // referencia a tabla terminals //
                   ->nullOnDelete(); // si borras una terminal, borra sus productos
        });
            // 👉 asignar el primer terminal a todos los productos existentes
            $firstTerminalId = \App\Models\Terminal::min('id'); 
            if ($firstTerminalId) {
                \App\Models\Product::whereNull('terminal_id')
                    ->update(['terminal_id' => $firstTerminalId]);
            }

            // 👉 luego cambiar a NOT NULL
            Schema::table('products', function (Blueprint $table) {
                $table->foreignId('terminal_id')
                    ->default(\App\Models\Terminal::min('id'))
                    ->change();
            });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['terminal_id']);
            $table->dropColumn('terminal_id');
        });
    }
};
