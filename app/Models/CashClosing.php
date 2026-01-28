<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashClosing extends Model
{

    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'closing_date'  => 'date',
        'confirmed_at'  => 'datetime',
    ];

    #appends
    protected function formatCreatedAt(): Attribute
    {
        return new Attribute(
            get: fn() => $this->closing_date->format('d-m-Y g:i A')
        );
    }

    #relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function terminal()
    {
        return $this->belongsTo(Terminal::class);
    }

    #scopes
    protected function scopeResponsible($query, $user_id)
    {
        if ($user_id) {
            return $query->where('user_id', $user_id);
        }
    }
    protected function scopeTerminal($query, $terminal_id)
    {
        if ($terminal_id) {
            return $query->where('terminal_id', $terminal_id);
        }
    }

    public function scopeDate($query, $days, $startDate, $endDate)
    {

        switch ($days) {
            case '1': //hoy
                return $query->whereDate('closing_date', Carbon::today());
                break;
            case '2': //esta semana
                $startWeek = Carbon::today()->startOfWeek();
                $endWeek = Carbon::today()->endOfWeek()->endOfDay();
                return $query->whereBetween('closing_date', [$startWeek, $endWeek]);
                break;

            case '3': //hace 7 dias
                $startDay = Carbon::today()->subDays(7);
                $endDay = Carbon::today()->endOfDay();
                return $query->whereBetween('closing_date', [$startDay, $endDay]);
                break;

            case '4': //La semana pasada
                $startWeek = Carbon::now()->subWeek()->startOfWeek();
                $endWeek = Carbon::now()->subWeek()->endOfWeek()->endOfDay();
                return $query->whereBetween('closing_date', [$startWeek, $endWeek]);
                break;

            case '5': //hace 5 dias
                $startDay = Carbon::today()->subDays(15);
                $endDay = Carbon::today()->endOfDay();
                return $query->whereBetween('closing_date', [$startDay, $endDay]);
                break;

            case '6': //este mes
                $startWeek = Carbon::today()->startOfMonth();
                $endWeek = Carbon::today()->endOfMonth()->endOfDay();
                return $query->whereBetween('closing_date', [$startWeek, $endWeek]);
                break;

            case '7': //mes pasado
                $startWeek = Carbon::today()->subMonth()->startOfMonth();
                $endWeek = Carbon::today()->subMonth()->endOfMonth()->endOfDay();
                return $query->whereBetween('closing_date', [$startWeek, $endWeek]);
                break;

            case '8': //Rango de fechas
                if ($startDate && $endDate) {
                    $startDate = Carbon::parse($startDate);
                    $endDate = Carbon::parse($endDate)->endOfDay();
                    return $query->whereBetween('closing_date', [$startDate, $endDate]);
                }
                break;

            default:

                break;
        }
    }

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function isConfirmed(): bool
    {
        return !is_null($this->confirmed_at);
    }

    public function scopeConfirmed($query)
    {
        return $query->whereNotNull('confirmed_at');
    }

    public function scopePending($query)
    {
        return $query->whereNull('confirmed_at');
    }
}
