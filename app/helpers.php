<?php

use App\Models\Terminal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;
if (! function_exists('rounded')) {
    function rounded($value, $decimals = 0): string
    {
        $value = round($value, 0, PHP_ROUND_HALF_EVEN);

        return bcdiv($value, 1, $decimals);
    }
}

if (! function_exists('formatToCop')) {
    function formatToCop($value)
    {
        return '$ '.number_format($value, 0, '.', ',');
    }
}

if (! function_exists('getUrlLogo')) {
    function getUrlLogo()
    {
         if (App::isLocal()) {
            return 'https://farmavivi.site/storage/images/system/logo-default.png';
         }
         else{
        if (Storage::exists('images/system/logo.png')) {
            return Storage::url('images/system/logo.png');
        } else {
            return Storage::url('images/system/logo-default.png');
        }
    }
    }
}

if (! function_exists('getTerminal')) {
    function getTerminal(): Terminal
    {

        $user = auth()->user();
        $key = 'terminals';

        if (! Cache::has($key)) {
            $terminals = Terminal::with('users')->where('status', Terminal::ACTIVE)->get();
            Cache::put($key, $terminals, now()->addDay());
        }

        $terminals = Cache::get($key);

        $terminal = new Terminal();

        foreach ($terminals as $value) {

            if ($value->users) {

                foreach ($value->users as $item) {

                    if ($item->id === $user->id) {
                        $terminal = $value;
                        break;
                    }
                }

                if ($terminal->id) {
                    break;
                }
            }
        }

        return $terminal;
    }
}

if (! function_exists('hasTerminal')) {
    function hasTerminal(): bool
    {
        $terminal = getTerminal();

        return $terminal->id ? true : false;
    }
}

if (! function_exists('getDays')) {
    function getDays(Carbon $from, $to, bool $text = true)
    {
        if ($to) {

            $days = $from->lte($to) ? $from->diffInDays($to) + 1 : 0;

            return $text ? $days.' días' : $days;
        }

        return 'No aplica';
    }
}

if (! function_exists('isRoot')) {
    function isRoot()
    {
        return session()->exists('root');
    }
}

if (! function_exists('calculateDiscountPercentage')) {
    function calculateDiscountPercentage(int $price, $amount, int $discount)
    {
        $total = $price * $amount;

        if ($discount === 0) {
            return 0;
        }

        if ($total <= 0 || $discount < 0) {
            throw new InvalidArgumentException('El valor de precio y el descuento deben igual o mayor a cero');
        }

        $percentage = ($discount / $total) * 100;

        return $percentage;
    }
}

if(!function_exists('factusIsEnabled')){
    function factusIsEnabled(): bool
    {
        return config('services.factus.enabled', false);
    }
}
