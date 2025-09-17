<?php

namespace App\Observers;

use App\Models\Bill;
use App\Services\FactusConfigurationService;
use App\Services\NumberingRangeService;

class BillObserver
{
    public function creating(Bill $bill)
    {
       // if (! FactusConfigurationService::isApiEnabled()) {
            $range = NumberingRangeService::nextNumber();
            $bill->number = $range->prefix.' - '.$range->current;
            $bill->numbering_range_id = $range->id;
            NumberingRangeService::incrementNumber($range);
       // }
    }
}
