<?php

namespace App\Http\Livewire\Admin\CashClosing;

use App\Models\CashClosing;
use App\Models\Terminal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GenerateDailyClosingsService
{
    public function generate(
        ?Carbon $fromDate = null,
        ?Carbon $toDate = null
    ): void {
        $terminals = Terminal::all();

        foreach ($terminals as $terminal) {
            $this->generateForTerminal($terminal, $fromDate, $toDate);
        }
    }

    protected function generateForTerminal(
        Terminal $terminal,
        ?Carbon $fromDate = null,
        ?Carbon $toDate = null
    ): void {
        $calculator = app(CashClosingCalculator::class);

        $lastConfirmed = CashClosing::where('terminal_id', $terminal->id)
            ->whereNotNull('confirmed_at')
            ->orderByDesc('closing_date')
            ->first();

        $startDate = $fromDate
            ?? optional($lastConfirmed?->closing_date)->addDay()
            ?? $terminal->created_at->startOfDay();

        $endDate = $toDate ?? now()->subDay()->startOfDay();

        if ($startDate->gt($endDate)) {
            return;
        }

        DB::transaction(function () use (
            $terminal,
            $calculator,
            $lastConfirmed,
            $startDate,
            $endDate
        ) {
            $previousClosing = $lastConfirmed;

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {

                $exists = CashClosing::where('terminal_id', $terminal->id)
                    ->whereDate('closing_date', $date)
                    ->exists();

                if ($exists) {
                    $previousClosing = CashClosing::where('terminal_id', $terminal->id)
                        ->whereDate('closing_date', $date)
                        ->first();
                    continue;
                }

                $data = $calculator->calculate(
                    $terminal,
                    $date,
                    $previousClosing
                );

                $closing = CashClosing::create(array_merge($data, [
                    'terminal_id'   => $terminal->id,
                    'closing_date'  => $date,
                    'confirmed_at'     => null,
                    'user_id'       => 1,
                    'confirmed_by'  => null,
                    'price' => 0,
                    'observations' => 'Generado automaticamente'
                ]));

                $previousClosing = $closing;
            }
        });
    }
}
