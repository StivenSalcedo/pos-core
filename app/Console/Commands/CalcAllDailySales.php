<?php

namespace App\Console\Commands;

use App\Models\Bill;
use App\Models\DailySale;
use App\Models\DailySaleDetail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CalcAllDailySales extends Command
{

    protected $signature = 'daily-sales {arg?}';
    protected $description = 'Calcula las ventas diarias desde que se comenzo a facturar';

    public function handle()
    {
        $this->info('Ejecutando tarea');
        $this->newLine(2);

        if ($this->arguments()['arg'] === 'all') {
            $this->calcAll();
        }else{
            $this->calcDay();
        }

        $this->newLine(2);
        $this->info('Tarea finalizada');
        return Command::SUCCESS;
    }

    protected function calcDay()
    {
        $yesterdayDate = now()->startOfDay()->subDay();

        $bills = Bill::enabled()->whereDate('created_at', $yesterdayDate)->orderBy('created_at', 'ASC')->get();

        DailySale::whereDate('creation_date', $yesterdayDate)->delete();

        $resume = $this->genereateResume($bills, $yesterdayDate);

        DailySale::create($resume->toArray());

        $this->info('Se calculo el día ' . $yesterdayDate->format('d-m-Y'));
    }

    protected function calcAll()
    {
        $this->resetTables();

        $startDate = Bill::enabled()->orderBy('created_at', 'ASC')->first()->created_at->startOfDay();

        $bar = $this->output->createProgressBar($startDate->diffInDays(now()->startOfDay()));

        $bar->start();

        try {
            DB::beginTransaction();

            while ($startDate->lt(now()->startOfDay())) {

                $bills = Bill::enabled()->whereDate('created_at', $startDate)->oldest()->get();

                $resume = $this->genereateResume($bills, $startDate);

                DailySale::create($resume->toArray());

                $startDate->addDay();

                $bar->advance();
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->info($th);
        }
    }

    protected function resetTables()
    {
        DailySaleDetail::truncate();
        DailySale::truncate();
        $this->info('Se eliminó las tablas de ventas diarias');
    }

    protected function genereateResume(Collection $bills, Carbon $startDate): Collection
    {
        $collect = [
            'creation_date' => $startDate,
            'subtotal_amount' => 0,
            'discount_amount' => 0,
            'inc_amount' => 0,
            'iva_amount' => 0,
            'total_amount' => 0,
        ];

        if ($bills->count()) {
            $collect = [
                'creation_date' => $startDate,
                'from' => $bills->first()->id,
                'to' => $bills->last()->id,
                'subtotal_amount' => $bills->sum('subtotal'),
                'discount_amount' => $bills->sum('discount'),
                'inc_amount' => $bills->sum('inc'),
                'iva_amount' => $bills->sum('iva'),
                'total_amount' => $bills->sum('total'),
            ];
        } else {
            $bill = Bill::whereDate('created_at', '<=', $startDate)->enabled()->latest()->take(1)->get()->first();
            $collect['from'] = $bill->id;
            $collect['to'] = $bill->id;
        }

        return collect($collect);
    }
}
