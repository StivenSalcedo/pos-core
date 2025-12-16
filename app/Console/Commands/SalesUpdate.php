<?php

namespace App\Console\Commands;

use App\Models\Bill;
use App\Models\DetailBill;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesUpdate extends Command
{
   protected $signature = '
    sales:update
    {--filter= : Tipo de filtro de fecha (0–8)}
    {--from= : Fecha inicio Y-m-d (solo filtro 8)}
    {--to= : Fecha fin Y-m-d (solo filtro 8)}
';

    protected $description = 'Actualiza la tabla sales a partir de facturas y servicios';

    public function handle()
    {
        [$from, $to] = $this->resolveDateRange();

        $this->info("Procesando sales desde {$from->toDateString()} hasta {$to->toDateString()}");

        DB::beginTransaction();

        try {

            /* ======================================================
             | 1. LIMPIAR SALES DEL RANGO
             ====================================================== */
            Sale::whereBetween('created_at', [$from, $to])->delete();

            /* ======================================================
             | 2. FACTURAS → SALES
             ====================================================== */
            $details = DetailBill::with(['bill'])
                ->whereBetween('created_at', [$from, $to])
                ->whereRelation('bill', 'status', Bill::ACTIVA)
                ->get();

            foreach ($details as $item) {

                Sale::create([
                    'product_id'        => $item->product_id,
                    'quantity'          => $item->amount,
                    'units'             => $item->units ?? 0,
                    'total'             => $item->total,

                    'payment_method_id' => $item->bill->payment_method_id,
                    'user_id'           => $item->bill->user_id,
                    'bill_id'           => $item->bill_id,
                    'service_id'        => null,
                    'source'            => 'bill',

                    'created_at'        => $item->created_at,
                    'updated_at'        => now(),
                ]);
            }

            /* ======================================================
             | 3. SERVICIOS → SALES (SOLO PAGADOS COMPLETOS)
             ====================================================== */
            $services = Service::with(['products', 'payments'])
                ->whereBetween('created_at', [$from, $to])
                ->get();

            foreach ($services as $service) {

                $totalProducts = $service->products->sum('total');
                $totalPayments = $service->payments->sum('amount');

                // ❌ servicio no completamente pagado
                if ($totalProducts != $totalPayments) {
                    continue;
                }

                foreach ($service->products as $product) {

                    Sale::create([
                        'product_id'        => $product->product_id,
                        'quantity'          => $product->quantity,
                        'units'             => $product->units ?? 0,
                        'total'             => $product->total,

                        // ⚠️ toma el método de pago del primer pago
                        'payment_method_id' => $service->payments->first()->payment_method_id,
                        'user_id'           => $service->user_id,
                        'bill_id'           => null,
                        'service_id'        => $service->id,
                        'source'            => 'service',

                        'created_at'        => $service->created_at,
                        'updated_at'        => now(),
                    ]);
                }
            }

            DB::commit();

            $this->info('Sales actualizadas correctamente');

            Log::info('SalesUpdate ejecutado', [
                'from' => $from->toDateString(),
                'to'   => $to->toDateString(),
            ]);

            return Command::SUCCESS;

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Error en SalesUpdate', [
                'error' => $e->getMessage(),
            ]);

            $this->error('Error al actualizar sales');
            $this->error($e->getMessage());

            return Command::FAILURE;
        }
    }

    /* ======================================================
     | RESOLVER RANGO DE FECHAS
     ====================================================== */
   private function resolveDateRange(): array
{
    $filter = (int) $this->option('filter');

    return match ($filter) {

        // 0 → Todos (⚠️ solo si quieres permitirlo)
        0 => [
            now()->subYears(10)->startOfDay(),
            now()->endOfDay()
        ],

        // 1 → Hoy
        1 => [
            now()->startOfDay(),
            now()->endOfDay()
        ],

        // 2 → Esta semana
        2 => [
            now()->startOfWeek(),
            now()->endOfWeek()
        ],

        // 3 → Últimos 7 días
        3 => [
            now()->subDays(6)->startOfDay(),
            now()->endOfDay()
        ],

        // 4 → Semana pasada
        4 => [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek()
        ],

        // 5 → Hace 15 días
        5 => [
            now()->subDays(14)->startOfDay(),
            now()->endOfDay()
        ],

        // 6 → Este mes
        6 => [
            now()->startOfMonth(),
            now()->endOfMonth()
        ],

        // 7 → Mes pasado
        7 => [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth()
        ],

        // 8 → Rango manual
        8 => [
            \Carbon\Carbon::parse($this->option('from'))->startOfDay(),
            \Carbon\Carbon::parse($this->option('to'))->endOfDay()
        ],

        // Default → ayer
        default => [
            now()->subDay()->startOfDay(),
            now()->subDay()->endOfDay()
        ],
    };
}

}
