<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBill extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    //relations
    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function presentation(): Attribute
    {
        return new Attribute(
            get: fn ($value) => json_decode($value),
            set: fn ($value) => json_encode($value)
        );
    }

    public function documentTaxes()
    {
        return $this->morphMany(DocumentTax::class, 'document_taxeable');
    }

    //scopes
    public function scopeSearch($query, $filter, $search)
    {
        return $query->whereHas('product', function (Builder $query) use ($filter, $search) {
            $query->where($filter, 'LIKE', '%'.$search.'%');
        });
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
}
