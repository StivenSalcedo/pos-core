<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver {
    
    public function updating(Product $product){
        if (intval($product->has_presentations)) {
            $product->quantity = 0;
            $product->units = 0;
        }
    }

    public function creating(Product $product){
        if (intval($product->has_presentations)) {
            $product->quantity = 0;
            $product->units = 0;
        }
    }

}
