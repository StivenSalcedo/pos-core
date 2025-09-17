<?php

namespace App\Providers;

use App\Models\Bill;
use App\Models\Product;
use App\Observers\BillObserver;
use App\Observers\ProductObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {

    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    public function boot() {
        Product::observe(ProductObserver::class);
        Bill::observe(BillObserver::class);
    }

    public function shouldDiscoverEvents() {
        return false;
    }
}
