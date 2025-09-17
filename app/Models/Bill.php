<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Bill extends Model
{
    use HasFactory;

    const ACTIVA = '0';

    const ANULADA = '1';

    protected $guarded = ['id'];

    protected $appends = [
        'change',
    ];

    // Appends
    protected function isElectronic(): Attribute
    {
        return new Attribute(
            get: fn () => $this->electronicBill ? true : false
        );
    }

    public function formatCreatedAt(): Attribute
    {
        return new Attribute(
            get: fn () => $this->created_at->format('d-m-Y h:i')
        );
    }

    public function finalTotal(): Attribute
    {
        return new Attribute(
            get: fn () => $this->total + $this->tip
        );
    }

    public function change(): Attribute
    {
        return new Attribute(
            get: fn () => $this->cash - ($this->total + $this->tip)
        );
    }

    protected function isValidated(): Attribute
    {
        return new Attribute(
            get: fn () => $this->electronicBill && $this->electronicBill->is_validated
        );
    }

    // Relationships
    public function details()
    {
        return $this->hasMany(DetailBill::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function finance()
    {
        return $this->hasOne(Finance::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function numberingRange()
    {
        return $this->belongsTo(NumberingRange::class);
    }

    public function terminal()
    {
        return $this->belongsTo(Terminal::class);
    }

    public function documentTaxes()
    {
        return $this->morphMany(DocumentTax::class, 'document_taxeable');
    }

    public function electronicBill()
    {
        return $this->hasOne(ElectronicBill::class);
    }

    public function electronicCreditNote()
    {
        return $this->hasOne(ElectronicCreditNote::class);
    }

    public function getChangeAttribute()
    {
        return $this->cash - $this->total;
    }

    // Scopes
    public function scopeSearch($query, $filter, $search)
    {
        if ($filter === 'id') {
            $query->where($filter, 'LIKE', '%' . $search . '%');
        }elseif ($filter === 'names') {
            $query->whereHas('customer',  function (Builder $query) use ($filter, $search) {
                $query->where($filter, 'LIKE', '%' . $search . '%');
            });
        }elseif ($filter === 'name') {
            $query->whereHas('user',  function (Builder $query) use ($filter, $search) {
                $query->where($filter, 'LIKE', '%' . $search . '%');
            });
        }
    }

    public function scopeTerminal($query, $terminal_id)
    {
        if ($terminal_id) {
            return $query->where('terminal_id', $terminal_id);
        }
    }

    public function scopeEnabled($query)
    {
        return $query->where('status', self::ACTIVA);
    }

    public function scopeStatus($query, $status)
    {

        switch ($status) {
            case '1':
                $query->where('status', '0');
                break;
            case '2':
                $query->where('status', '1');
                break;
        }
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
}
