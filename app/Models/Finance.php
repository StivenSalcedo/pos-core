<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{

    use HasFactory;

    protected $guarded = ['id'];

    protected $attributes = [
        'status' => '1'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    protected $withCount = ['details'];

    #append
    public function getPaidAttribute()
    {
        if ($this->details->count()) {
            return $this->details->sum('value');
        }
        return 0;
    }

    public function getPendingAttribute()
    {
        $pending = $this->bill->total;
        if ($this->details->count()) {
            return $pending - $this->details->sum('value');
        }
        return $pending;
    }

    public function getExpiresInAttribute()
    {
        return $this->due_date ? $this->due_date->format('d-m-Y') : 'No especificado';
    }

    #relations
    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function customer()
    {
        return $this->hasOneThrough(Customer::class, Bill::class, 'id', 'id', 'bill_id', 'customer_id');
    }

    public function details()
    {
        return $this->hasMany(DetailFinance::class);
    }

    #scopes
    public function scopeSearch($query, $filter, $search)
    {

        if ($filter == 'bill_id') {
            return $query->where($filter, 'LIKE', '%' . $search . '%');
        }

        return $query->whereHas('customer',  function (Builder $query) use ($filter, $search) {
            $query->where($filter, 'LIKE', '%' . $search . '%');
        });
    }

    public function scopeDate($query, $days, $startDate, $endDate)
    {

        switch ($days) {
            case '1': //hoy
                return $query->whereDate('created_at', Carbon::today());
                break;
            case '2': //esta semana
                $startWeek = Carbon::today()->startOfWeek();
                $endWeek = Carbon::today()->endOfWeek()->endOfDay();
                return $query->whereBetween('created_at', [$startWeek, $endWeek]);
                break;

            case '3': //hace 7 dias
                $startDay = Carbon::today()->subDays(7);
                $endDay = Carbon::today()->endOfDay();
                return $query->whereBetween('created_at', [$startDay, $endDay]);
                break;

            case '4': //La semana pasada
                $startWeek = Carbon::now()->subWeek()->startOfWeek();
                $endWeek = Carbon::now()->subWeek()->endOfWeek()->endOfDay();
                return $query->whereBetween('created_at', [$startWeek, $endWeek]);
                break;

            case '5': //hace 5 dias
                $startDay = Carbon::today()->subDays(15);
                $endDay = Carbon::today()->endOfDay();
                return $query->whereBetween('created_at', [$startDay, $endDay]);
                break;

            case '6': //este mes
                $startWeek = Carbon::today()->startOfMonth();
                $endWeek = Carbon::today()->endOfMonth()->endOfDay();
                return $query->whereBetween('created_at', [$startWeek, $endWeek]);
                break;

            case '7': //mes pasado
                $startWeek = Carbon::today()->subMonth()->startOfMonth();
                $endWeek = Carbon::today()->subMonth()->endOfMonth()->endOfDay();
                return $query->whereBetween('created_at', [$startWeek, $endWeek]);
                break;

            case '8': //Rango de fechas
                if ($startDate && $endDate) {
                    $startDate = Carbon::parse($startDate);
                    $endDate = Carbon::parse($endDate)->endOfDay();
                    return $query->whereBetween('created_at', [$startDate, $endDate]);
                }
                break;

            default:

                break;
        }
    }

    public function scopeFilterStatus($query, $filter)
    {
        switch ($filter) {
            case '1':
                $query->where('status', '0');
                break;
            case '2':
                $query->where('status', '1');
                break;
            case '3':
                $query->whereDate('due_date', '<', now());
                break;
        }
    }
}
