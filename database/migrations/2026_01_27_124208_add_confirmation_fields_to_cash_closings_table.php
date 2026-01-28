<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_closings', function (Blueprint $table) {
            // Fecha real del cierre
            $table->timestamp('closing_date')
            ->nullable()
                ->after('terminal_id')
                ->comment('Fecha del cierre de caja');

            // Confirmación
            $table->timestamp('confirmed_at')
                ->nullable()
                ->after('updated_at');

            $table->foreignId('confirmed_by')
                ->nullable()
                ->after('confirmed_at')
                ->constrained('users')
                ->nullOnDelete();

            // Evitar cierres duplicados por sede y fecha
            $table->unique(
                ['terminal_id', 'closing_date'],
                'unique_terminal_closing_date'
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cash_closings', function (Blueprint $table) {
            $table->dropUnique('unique_terminal_closing_date');

            $table->dropForeign(['confirmed_by']);
            $table->dropColumn([
                'closing_date',
                'confirmed_at',
                'confirmed_by',
            ]);
        });
    }
};
