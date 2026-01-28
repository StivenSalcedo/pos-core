<?php

namespace App\Http\Livewire\Admin\CashClosing;

use App\Models\Bill;
use App\Models\DetailFinance;
use App\Models\Output;
use App\Models\ServicePayment;
use App\Models\CashClosing;
use App\Models\Terminal;
use App\Enums\CashRegisters;
use App\Models\PaymentMethod;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CashClosingCalculator
{
    public function calculate(
        Terminal $terminal,
        Carbon $date,
        ?CashClosing $previousClosing = null
    ): array {

        $from = $date->copy()->startOfDay();
        $to   = $date->copy()->endOfDay();

        $base = $previousClosing?->price ?? 0;

        [$cash, $credit_card, $debit_card, $transfer, $tip] =
            $this->getBills($terminal, $from, $to);

        [$cash, $credit_card, $debit_card, $transfer] =
            $this->getFinances($terminal, $from, $to, $cash, $credit_card, $debit_card, $transfer);

        [$cash, $credit_card, $debit_card, $transfer] =
            $this->getPaidServices($terminal, $from, $to, $cash, $credit_card, $debit_card, $transfer);

        $outputs = $this->getOutputs($terminal, $from, $to);

        $total_sales = $cash + $credit_card + $debit_card + $transfer;
        if (($cash + $base) < $outputs) {
            $base = $outputs - $cash;
        }

        $cash_register = ($cash + $base) - $outputs;

        return compact(
            'base',
            'cash',
            'credit_card',
            'debit_card',
            'transfer',
            'tip',
            'outputs',
            'total_sales',
            'cash_register'
        );
    }

    /* ========================= PRIVATE ========================= */

    private function getBills(Terminal $terminal, $from, $to): array
    {
        $bills = Bill::whereBetween('created_at', [$from, $to])
            ->where('terminal_id', $terminal->id)
            ->where('status', Bill::ACTIVA)
            ->doesntHave('finance')
            ->get(['total', 'tip', 'payment_method_id']);

        return [
            $bills->where('payment_method_id', PaymentMethod::CASH)->sum('total'),
            $bills->where('payment_method_id', PaymentMethod::CREDIT_CARD)->sum('total')
                + $bills->where('payment_method_id', PaymentMethod::CREDIT_CARD)->sum('tip'),
            $bills->where('payment_method_id', PaymentMethod::DEBIT_CARD)->sum('total')
                + $bills->where('payment_method_id', PaymentMethod::DEBIT_CARD)->sum('tip'),
            $bills->where('payment_method_id', '>=', PaymentMethod::TRANSFER)->sum('total')
                + $bills->where('payment_method_id', '>=', PaymentMethod::TRANSFER)->sum('tip'),
            $bills->sum('tip'),
        ];
    }

    private function getFinances(
        Terminal $terminal,
        $from,
        $to,
        $cash,
        $credit,
        $debit,
        $transfer
    ): array {
        $finances = DetailFinance::where('terminal_id', $terminal->id)
            ->whereBetween('created_at', [$from, $to])
            ->get();

        return [
            $cash     + $finances->where('payment_method_id', PaymentMethod::CASH)->sum('value'),
            $credit   + $finances->where('payment_method_id', PaymentMethod::CREDIT_CARD)->sum('value'),
            $debit    + $finances->where('payment_method_id', PaymentMethod::DEBIT_CARD)->sum('value'),
            $transfer + $finances->where('payment_method_id', '>=', PaymentMethod::TRANSFER)->sum('value'),
        ];
    }

    private function getPaidServices(
        Terminal $terminal,
        $from,
        $to,
        $cash,
        $credit,
        $debit,
        $transfer
    ): array {

        $services = DB::table('service_payments')
            ->selectRaw('payment_method_id, SUM(amount) total')
            ->whereBetween('created_at', [$from, $to])
            ->whereIn('service_id', function ($q) use ($terminal) {
                $q->select('id')
                    ->from('services')
                    ->where('terminal_id', $terminal->id);
            })
            ->groupBy('payment_method_id')
            ->get();

        return [
            $cash     + $services->where('payment_method_id', PaymentMethod::CASH)->sum('total'),
            $credit   + $services->where('payment_method_id', PaymentMethod::CREDIT_CARD)->sum('total'),
            $debit    + $services->where('payment_method_id', PaymentMethod::DEBIT_CARD)->sum('total'),
            $transfer + $services->where('payment_method_id', '>=', PaymentMethod::TRANSFER)->sum('total'),
        ];
    }

    private function getOutputs(Terminal $terminal, $from, $to): int
    {
        return Output::where('terminal_id', $terminal->id)
            ->whereBetween('created_at', [$from, $to])
            ->where('from', CashRegisters::MAIN)
            ->sum('price');
    }
}
