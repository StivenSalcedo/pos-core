<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

    public function register() {

    }


    public function boot() {
        date_default_timezone_set('America/Bogota');
        Blade::directive('formatToCop', function ($value) {
            return "<?php echo '$ ' . number_format($value, 0, '.', ','); ?>";
        });
    }
}
