<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const ACTIVE = '0';

    const INACTIVE = '1';

    const TOP_INACTIVE = '1';

    const TOP_ACTIVE = '0';

    const HAS_PRESENTATION_INACTIVE = '1';

    const HAS_PRESENTATION_ACTIVE = '0';

    protected $attributes = [
        'top' => '1',
        'has_presentations' => '1',
        'quantity' => 0,
        'units' => 0,
        'status' => '0',
    ];

    public function presentations()
    {
        return $this->hasMany(Presentation::class);
    }

    public function getStockUnitsLabelAttribute()
    {
        if (intval($this->has_inventory)) {
            return 'No aplica';
        }

        if (intval($this->has_presentations)) {
            return $this->stock;
        }

        $units = $this->units - ($this->stock * $this->quantity);

        return $this->stock.'-'.$units;
    }

    public function getStockUnitsAttribute()
    {

        if (intval($this->has_presentations)) {
            return $this->stock;
        }

        return $this->units - ($this->stock * $this->quantity);

    }

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function taxRates(): BelongsToMany
    {
        return $this->belongsToMany(TaxRate::class)->withPivot('value');
    }

    public function scopeFilterBarcode($query, $filter, $search)
    {
        if ($filter === 'reference') {
            $query->orWhere('barcode', $search);
        }
    }

    public function terminal()
    {
        return $this->belongsTo(Terminal::class);
    }

     public function scopeTerminal($query, $terminal_id)
    {
        if ($terminal_id) {
            return $query->where('terminal_id', $terminal_id);
        }
    }
}
