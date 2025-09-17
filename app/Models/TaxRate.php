<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TaxRate extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const EXCLUDED_IVA = 1;

    const ACTIVE = '0';

    protected $attributes = [
        'default' => 0,
    ];

    //appends
    protected function symbolWithRate(): Attribute
    {
        return new Attribute(
            get: function () {
                $symbol = $this->has_percentage ? '% ' : '$ ';

                return $symbol.$this->rate;
            }
        );
    }

    protected function formatRate(): Attribute
    {
        return new Attribute(
            get: function () {
                $symbol = $this->has_percentage ? '% - ' : '$ -';

                return '('.$this->tribute_name.') '.$symbol.$this->rate;
            }
        );
    }

    protected function formatName(): Attribute
    {
        return new Attribute(
            get: fn () => '('.$this->tribute->name.') '.$this->rate
        );
    }

    protected function formatName2(): Attribute
    {
        return new Attribute(
            get: function () {
                $symbol = $this->has_percentage ? '% - ' : '$ - ';

                return '('.$this->tribute_name.') '.$symbol.$this->rate.' '.$this->name;
            }
        );
    }

    protected function tributeName(): Attribute
    {
        return new Attribute(
            get: fn () => $this->tribute->name
        );
    }

    protected function type(): Attribute
    {
        return new Attribute(
            get: fn () => $this->has_percentage == 1 ? 'porcentaje' : 'pesos'
        );
    }

    //ralations
    public function tribute()
    {
        return $this->belongsTo(Tribute::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    //scopes
    protected function scopeEnabled($query)
    {
        return $query->where('status', self::ACTIVE);
    }
}
